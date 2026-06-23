<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Determina si el usuario autenticado es administrador.
     */
    private function esAdmin(): bool
    {
        return Auth::check() && Auth::user()->esAdministrador();
    }


    private function calcularHoraFin(string $horaInicio, int $duracionMinutos): string
    {
        return Carbon::parse($horaInicio)
            ->addMinutes($duracionMinutos)
            ->format('H:i:s');
    }


    private function estaDentroDeJornada(string $horaInicio, string $horaFin): bool
    {
        $inicioJornada = Carbon::parse('15:00:00');
        $finJornada = Carbon::parse('18:00:00');

        $inicio = Carbon::parse($horaInicio);
        $fin = Carbon::parse($horaFin);

        return $inicio->greaterThanOrEqualTo($inicioJornada)
            && $fin->lessThanOrEqualTo($finJornada);
    }

    private function existeCruceHorario(
        int $sacerdoteId,
        string $fecha,
        string $horaInicio,
        string $horaFin,
        ?int $citaId = null
    ): bool {
        return Cita::where('sacerdote_id', $sacerdoteId)
            ->whereDate('fecha', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->when($citaId, function ($query) use ($citaId) {
                $query->where('id', '!=', $citaId);
            })
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->where('hora', '<', $horaFin)
                    ->where('hora_fin', '>', $horaInicio);
            })
            ->exists();
    }

    private function obtenerHorariosDisponibles($sacerdoteId, $fecha)
    {
        $inicioJornada = Carbon::parse('15:00');
        $finJornada = Carbon::parse('18:00');

        $duracionSlot = 15; // bloques base

        $horarios = [];

        while ($inicioJornada < $finJornada) {

            $horaInicio = $inicioJornada->format('H:i:s');
            $horaFin = $inicioJornada->copy()->addMinutes($duracionSlot)->format('H:i:s');

            $ocupado = Cita::where('sacerdote_id', $sacerdoteId)
                ->whereDate('fecha', $fecha)
                ->where('estado', '!=', 'cancelada')
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where('hora', '<', $horaFin)
                        ->where('hora_fin', '>', $horaInicio);
                })
                ->exists();

            $horarios[] = [
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
                'ocupado' => $ocupado
            ];

            $inicioJornada->addMinutes($duracionSlot);
        }

        return $horarios;
    }


    /**
     * Muestra el listado de citas.
     * - Admin: ve todas las citas.
     * - Feligres: ve solo sus propias citas.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();

        // Consulta base
        $query = Cita::with(['feligres', 'sacerdote']);

        // Si NO es admin, solo puede ver sus propias citas
        if (!$this->esAdmin()) {
            $query->where('feligres_id', $usuario->id);
        }

        // Filtrar por sacerdote (visible para ambos)
        if ($request->filled('sacerdote_id')) {
            $query->where('sacerdote_id', $request->sacerdote_id);
        }

        // Filtrar por fecha (visible para ambos)
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;

            if ($this->esAdmin()) {
                // Admin puede buscar por nombre, apellidos o teléfono del feligrés
                $query->where(function ($q) use ($search) {
                    $q->whereHas('feligres', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%")
                            ->orWhere('telefono', 'like', "%{$search}%");
                    })
                    ->orWhere('tipo', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
                });
            } else {
                // Feligres busca solo dentro de SUS citas,
                // sin buscar por nombre de otros usuarios
                $query->where(function ($q) use ($search) {
                    $q->where('tipo', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%")
                        ->orWhereHas('sacerdote', function ($subQ) use ($search) {
                            $subQ->where('name', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%");
                        });
                });
            }
        }

        $citas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora', 'asc')
            ->paginate(15);

        // Mantener filtros en paginación
        $citas->appends($request->all());

        // Sacerdotes disponibles para filtros/formularios
        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        return view('citas.index', compact('citas', 'sacerdotes'));
    }

    /**
     * Muestra el formulario para crear cita.
     * - Admin: puede seleccionar cualquier feligrés.
     * - Feligres: solo puede crear cita para sí mismo.
     */
    public function create(Request $request)
    {
        $usuario = Auth::user();

        if ($this->esAdmin()) {
            $feligreses = User::where('rol', 'feligres')
                ->orderBy('name')
                ->get();
        } else {
            $feligreses = User::where('id', $usuario->id)->get();
        }

        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        $sacerdoteSeleccionado = $request->input(
            'sacerdote_id',
            $sacerdotes->first()->id ?? null
        );

        $fechaSeleccionada = $request->input(
            'fecha',
            now()->toDateString()
        );

        $horarios = [];

        if ($sacerdoteSeleccionado && $fechaSeleccionada) {
            $horarios = $this->obtenerHorariosDisponibles(
                $sacerdoteSeleccionado,
                $fechaSeleccionada
            );
        }

        return view('citas.create', compact(
            'feligreses',
            'sacerdotes',
            'horarios',
            'sacerdoteSeleccionado',
            'fechaSeleccionada'
        ));
    }


    /**
     * Guarda una nueva cita.
     * - Admin: puede crear para cualquier feligrés.
     * - Feligres: solo crea para sí mismo.
     * - Estado por defecto: pendiente
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();

        $rules = [
            'sacerdote_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'duracion_minutos' => 'required|integer|in:10,15,20,30',
            'tipo' => 'required|in:confesion,bautismo,matrimonio,orientacion',
            'descripcion' => 'nullable|string',
        ];

        // Admin puede indicar feligrés y notas internas
        if ($this->esAdmin()) {
            $rules['feligres_id'] = 'required|exists:users,id';
            $rules['notas_internas'] = 'nullable|string';
        } else {
            // Feligres no define feligrés manualmente ni notas internas
            $rules['feligres_id'] = 'nullable|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Si NO es admin, la cita siempre queda asociada al usuario autenticado
        if (!$this->esAdmin()) {
            $validated['feligres_id'] = $usuario->id;
            unset($validated['notas_internas']);
        }

        $horaInicio = $validated['hora'];
        $horaFin = $this->calcularHoraFin($horaInicio, (int) $validated['duracion_minutos']);

        if (!$this->estaDentroDeJornada($horaInicio, $horaFin)) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'La cita debe estar dentro del horario de atención: 3:00 PM a 6:00 PM.'
                ]);
        }

        if ($this->existeCruceHorario(
            (int) $validated['sacerdote_id'],
            $validated['fecha'],
            $horaInicio,
            $horaFin
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'El sacerdote ya tiene una cita programada dentro de ese rango horario. Por favor seleccione otro horario.'
                ]);
        }

        $validated['hora_fin'] = $horaFin;

        // Siempre inicia como pendiente
        $validated['estado'] = 'pendiente';

        Cita::create($validated);

        return redirect()->route('citas.index')
            ->with('success', 'Cita creada exitosamente.');
    }

    /**
     * Muestra el formulario de edición.
     * SOLO admin.
     */
    public function edit(Cita $cita)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $feligreses = User::where('rol', 'feligres')
            ->orderBy('name')
            ->get();

        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        return view('citas.edit', compact('cita', 'feligreses', 'sacerdotes'));
    }

    /**
     * Actualiza una cita.
     * SOLO admin.
     */
    public function update(Request $request, Cita $cita)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $validated = $request->validate([
            'feligres_id' => 'required|exists:users,id',
            'sacerdote_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'duracion_minutos' => 'required|integer|in:10,15,20,30',
            'tipo' => 'required|in:confesion,bautismo,matrimonio,orientacion',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'notas_internas' => 'nullable|string',
        ]);
        $horaInicio = $validated['hora'];
        $horaFin = $this->calcularHoraFin($horaInicio, (int) $validated['duracion_minutos']);

        if (!$this->estaDentroDeJornada($horaInicio, $horaFin)) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'La cita debe estar dentro del horario de atención: 3:00 PM a 6:00 PM.'
                ]);
        }

        if ($this->existeCruceHorario(
            (int) $validated['sacerdote_id'],
            $validated['fecha'],
            $horaInicio,
            $horaFin,
            $cita->id
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'El sacerdote ya tiene otra cita programada dentro de ese rango horario. Por favor seleccione otro horario.'
                ]);
        }

        $validated['hora_fin'] = $horaFin;

        $cita->update($validated);

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente.');
    }

    /**
     * Elimina una cita.
     * - Admin: puede eliminar cualquier cita.
     * - Feligres: solo puede eliminar SUS citas pendientes.
     */
    public function destroy(Cita $cita)
    {
        if ($this->esAdmin()) {
            $cita->delete();

            return redirect()->route('citas.index')
                ->with('success', 'Cita eliminada exitosamente.');
        }

        // Si es feligrés, solo puede eliminar sus propias citas pendientes
        if ($cita->feligres_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta cita.');
        }

        if ($cita->estado !== 'pendiente') {
            return redirect()->route('citas.index')
                ->with('error', 'Solo puedes eliminar citas en estado pendiente.');
        }

        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'Tu cita pendiente fue eliminada exitosamente.');
    }

    /**
     * Cambia el estado de una cita.
     * SOLO admin.
     */
    public function cambiarEstado(Request $request, Cita $cita)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);

        $cita->update([
            'estado' => $request->estado
        ]);

        return back()->with('success', 'Estado de la cita actualizado.');
    }
}
