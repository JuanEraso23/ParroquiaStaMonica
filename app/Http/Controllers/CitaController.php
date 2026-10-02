<?php

namespace App\Http\Controllers;

use App\Models\AgendaSacerdote;
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

    /**
     * Verifica si la agenda del sacerdote para esa fecha está abierta.
     */
    private function agendaEstaAbierta(int $sacerdoteId, string $fecha): bool
    {
        return AgendaSacerdote::where('sacerdote_id', $sacerdoteId)
            ->whereDate('fecha', $fecha)
            ->where('estado', 'abierta')
            ->exists();
    }

    private function obtenerHorariosDisponibles($sacerdoteId, $fecha, int $duracionConsulta = 20)
    {
        if (!$sacerdoteId || !$fecha) {
            return [];
        }

        $inicioJornada = Carbon::parse('15:00:00');
        $finJornada = Carbon::parse('18:00:00');

        // Cada opción inicia cada 15 minutos
        $intervaloEntreOpciones = 15;

        $horarios = [];

        while ($inicioJornada->copy()->addMinutes($duracionConsulta)->lessThanOrEqualTo($finJornada)) {
            $horaInicio = $inicioJornada->format('H:i:s');
            $horaFin = $inicioJornada->copy()->addMinutes($duracionConsulta)->format('H:i:s');

            $citaOcupada = Cita::with('feligres')
                ->where('sacerdote_id', $sacerdoteId)
                ->whereDate('fecha', $fecha)
                ->where('estado', '!=', 'cancelada')
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where('hora', '<', $horaFin)
                        ->where('hora_fin', '>', $horaInicio);
                })
                ->first();

            $horarios[] = [
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
                'duracion' => $duracionConsulta,
                'ocupado' => $citaOcupada !== null,
                'feligres_nombre' => $citaOcupada?->feligres?->nombre_completo,
                'tipo_cita' => $citaOcupada?->tipo_texto,
                'estado_cita' => $citaOcupada?->estado,
            ];

            $inicioJornada->addMinutes($intervaloEntreOpciones);
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
     * - Si la agenda del sacerdote está cerrada para esa fecha, se muestra aviso.
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

        $duracionSeleccionada = (int) $request->input('duracion_minutos', 20);

        $agendaAbierta = false;
        $horarios = [];

        if ($sacerdoteSeleccionado && $fechaSeleccionada) {
            $agendaAbierta = $this->agendaEstaAbierta(
                (int) $sacerdoteSeleccionado,
                $fechaSeleccionada
            );

            if ($agendaAbierta) {
                $horarios = $this->obtenerHorariosDisponibles(
                    $sacerdoteSeleccionado,
                    $fechaSeleccionada,
                    $duracionSeleccionada
                );
            }
        }

        return view('citas.create', compact(
            'feligreses',
            'sacerdotes',
            'horarios',
            'sacerdoteSeleccionado',
            'fechaSeleccionada',
            'duracionSeleccionada',
            'agendaAbierta'
        ));
    }

    /**
     * Guarda una nueva cita.
     * - Admin: puede crear para cualquier feligrés.
     * - Feligres: solo crea para sí mismo.
     * - Estado por defecto: pendiente
     * - Bloquea si la agenda del sacerdote está cerrada para esa fecha.
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

        // 1) Agenda debe estar abierta para ese sacerdote y esa fecha
        if (!$this->agendaEstaAbierta((int) $validated['sacerdote_id'], $validated['fecha'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'La agenda del sacerdote está cerrada para esa fecha. No se pueden registrar nuevas citas.'
                ]);
        }

        $horaInicio = $validated['hora'];
        $horaFin = $this->calcularHoraFin($horaInicio, (int) $validated['duracion_minutos']);

        // 2) Dentro de la jornada 15:00 - 18:00
        if (!$this->estaDentroDeJornada($horaInicio, $horaFin)) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'La cita debe estar dentro del horario de atención: 3:00 PM a 6:00 PM.'
                ]);
        }

        // 3) Sin cruces con otras citas del mismo sacerdote
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

        $agendaAbierta = $this->agendaEstaAbierta(
            (int) $cita->sacerdote_id,
            Carbon::parse($cita->fecha)->toDateString()
        );

        return view('citas.edit', compact('cita', 'feligreses', 'sacerdotes', 'agendaAbierta'));
    }

    /**
     * Actualiza una cita.
     * SOLO admin.
     * - No bloquea la edición normal de una cita existente.
     * - Solo bloquea si se intenta mover a una fecha/sacerdote con agenda cerrada.
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

        // Detectar si la cita cambia de sacerdote o de fecha
        $mismaFecha = Carbon::parse($cita->fecha)->toDateString()
            === Carbon::parse($validated['fecha'])->toDateString();

        $mismoSacerdote = (int) $cita->sacerdote_id === (int) $validated['sacerdote_id'];

        // Solo si cambia de sacerdote o fecha, se exige que la agenda esté abierta
        if ((!$mismaFecha || !$mismoSacerdote)
            && !$this->agendaEstaAbierta((int) $validated['sacerdote_id'], $validated['fecha'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha' => 'No se puede mover esta cita: la agenda del sacerdote está cerrada para esa fecha.'
                ]);
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
