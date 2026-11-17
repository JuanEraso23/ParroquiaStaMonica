<?php

namespace App\Http\Controllers;

use App\Models\AgendaSacerdote;
use App\Models\Cita;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaSacerdoteController extends Controller
{
    /**
     * Verifica que el usuario autenticado sea administrador.
     */
    private function esAdmin(): bool
    {
        return Auth::check() && Auth::user()->esAdministrador();
    }

    /**
     * Lista las agendas del día seleccionado para cada sacerdote.
     */
    public function index(Request $request)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $fecha = $request->input('fecha', now()->toDateString());

        try {
            $fechaCarbon = Carbon::parse($fecha);
        } catch (\Exception $e) {
            $fechaCarbon = now();
            $fecha = $fechaCarbon->toDateString();
        }

        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        $agendas = AgendaSacerdote::whereDate('fecha', $fecha)
            ->with(['usuarioApertura', 'usuarioCierre'])
            ->get()
            ->keyBy('sacerdote_id');

        // Citas no canceladas por sacerdote para esa fecha
        $citasPorSacerdote = Cita::whereDate('fecha', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->selectRaw('sacerdote_id, count(*) as total')
            ->groupBy('sacerdote_id')
            ->pluck('total', 'sacerdote_id');

        // ¿Ya pasó el plazo normal de las 12:00 PM para la fecha mostrada (si es hoy)?
        $ahora = now();
        $pasoElPlazoHoy = $fechaCarbon->isToday()
            && $ahora->greaterThan(Carbon::today()->setTime(12, 0));

        return view('agendas.index', compact(
            'fecha',
            'sacerdotes',
            'agendas',
            'citasPorSacerdote',
            'pasoElPlazoHoy'
        ));
    }

    /**
     * Abre (o reabre) la agenda de un sacerdote para una fecha.
     * Reglas:
     * - Solo administradores.
     * - Solo parroco o vicario.
     * - Dentro de la jornada 15:00 - 18:00.
     * - Después de las 12:00 PM del mismo día -> apertura extraordinaria con motivo.
     */
    public function abrir(Request $request)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $validated = $request->validate([
            'sacerdote_id' => 'required|exists:users,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'motivo_apertura_extraordinaria' => 'nullable|string|max:1000',
        ], [
            'fecha.after_or_equal' => 'No se puede abrir la agenda de una fecha pasada.',
            'hora_fin.after' => 'La hora final debe ser posterior a la hora inicial.',
        ]);

        // Verificar que el seleccionado sea sacerdote válido
        $sacerdote = User::find($validated['sacerdote_id']);
        if (!$sacerdote || !in_array($sacerdote->rol, ['parroco', 'vicario'], true)) {
            return back()
                ->withInput()
                ->withErrors(['sacerdote_id' => 'El usuario seleccionado no es un sacerdote válido.']);
        }

        // Validar jornada 15:00 - 18:00
        $inicio = Carbon::parse($validated['hora_inicio']);
        $fin = Carbon::parse($validated['hora_fin']);
        $jornadaInicio = Carbon::parse('15:00');
        $jornadaFin = Carbon::parse('18:00');

        if ($inicio->lessThan($jornadaInicio) || $fin->greaterThan($jornadaFin)) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora_inicio' => 'La jornada debe estar dentro del horario de atención: 3:00 PM a 6:00 PM.'
                ]);
        }

        // Determinar si es apertura fuera de plazo
        $ahora = now();
        $fechaAgenda = Carbon::parse($validated['fecha']);
        $fueraDePlazo = $fechaAgenda->isToday()
            && $ahora->greaterThan(Carbon::today()->setTime(12, 0));

        if ($fueraDePlazo && empty($validated['motivo_apertura_extraordinaria'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'motivo_apertura_extraordinaria' => 'Debe indicar un motivo para la apertura extraordinaria después de las 12:00 PM.'
                ]);
        }

        // Crear o actualizar la agenda del sacerdote para esa fecha
        $agenda = AgendaSacerdote::firstOrNew([
            'sacerdote_id' => $validated['sacerdote_id'],
            'fecha' => $validated['fecha'],
        ]);

        $agenda->fill([
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'estado' => 'abierta',
            'abierta_por' => Auth::id(),
            'abierta_en' => now(),
            'fuera_de_plazo' => $fueraDePlazo,
            'motivo_apertura_extraordinaria' => $validated['motivo_apertura_extraordinaria'] ?? null,
            'cerrada_por' => null,
            'cerrada_en' => null,
        ]);

        $agenda->save();

        return redirect()
            ->route('agendas.index', ['fecha' => $validated['fecha']])
            ->with('success', 'Agenda abierta exitosamente para ' . $sacerdote->nombre_completo . '.');
    }

    /**
     * Cierra la agenda de un sacerdote.
     * NO borra ni cancela citas existentes: solo impide nuevas.
     */
    public function cerrar(AgendaSacerdote $agenda)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $agenda->update([
            'estado' => 'cerrada',
            'cerrada_por' => Auth::id(),
            'cerrada_en' => now(),
        ]);

        return redirect()
            ->route('agendas.index', ['fecha' => $agenda->fecha->toDateString()])
            ->with('success', 'Agenda cerrada. Las citas existentes se mantienen intactas.');
    }
}
