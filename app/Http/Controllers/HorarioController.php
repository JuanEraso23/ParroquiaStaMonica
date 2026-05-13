<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HorarioController extends Controller
{
    /**
     * Determina si el usuario autenticado es administrador.
     */
    private function esAdmin(): bool
    {
        return Auth::check() && Auth::user()->esAdministrador();
    }

    /**
     * Duración estimada según el tipo de cita.
     */
    private function duracionEstimadaPorTipo(string $tipo): int
    {
        return match ($tipo) {
            'confesion' => 30,
            'orientacion' => 30,
            'bautismo' => 60,
            'matrimonio' => 90,
            default => 30,
        };
    }

    /**
     * Color visual según el tipo de cita.
     */
    private function colorPorTipo(string $tipo): string
    {
        return match ($tipo) {
            'confesion' => '#3B82F6',
            'bautismo' => '#10B981',
            'matrimonio' => '#8B5CF6',
            'orientacion' => '#EAB308',
            default => '#6B7280',
        };
    }

    /**
     * Calendario mensual informativo.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $esAdmin = $this->esAdmin();

        $anio = $request->get('anio', now()->year);
        $mes = $request->get('mes', now()->month);

        $fechaBase = Carbon::createFromDate($anio, $mes, 1)->startOfMonth();

        $inicioCalendario = $fechaBase->copy()->startOfWeek(Carbon::MONDAY);
        $finCalendario = $fechaBase->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        // ✅ TODOS ven TODAS las citas confirmadas y completadas
        $citasDelMes = Cita::with(['feligres', 'sacerdote'])
            ->whereIn('estado', ['confirmada', 'completada'])
            ->whereBetween('fecha', [
                $inicioCalendario->format('Y-m-d'),
                $finCalendario->format('Y-m-d'),
            ])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get()
            ->groupBy(function ($cita) {
                return Carbon::parse($cita->fecha)->format('Y-m-d');
            });

        $semanas = [];
        $diaActual = $inicioCalendario->copy();

        while ($diaActual <= $finCalendario) {
            $semana = [];

            for ($i = 0; $i < 7; $i++) {
                $fechaKey = $diaActual->format('Y-m-d');

                $semana[] = [
                    'fecha' => $diaActual->copy(),
                    'fecha_key' => $fechaKey,
                    'es_mes_actual' => $diaActual->month === $fechaBase->month,
                    'es_hoy' => $diaActual->isToday(),
                    'total_citas' => isset($citasDelMes[$fechaKey]) ? $citasDelMes[$fechaKey]->count() : 0,
                    'citas' => $citasDelMes[$fechaKey] ?? collect(),
                ];

                $diaActual->addDay();
            }

            $semanas[] = $semana;
        }

        $mesAnterior = $fechaBase->copy()->subMonth();
        $mesSiguiente = $fechaBase->copy()->addMonth();

        return view('horarios.index', compact(
            'semanas',
            'fechaBase',
            'mesAnterior',
            'mesSiguiente',
            'esAdmin'
        ));
    }

    /**
     * Vista diaria con línea de tiempo.
     */
    public function dia(string $fecha)
    {
        $usuario = Auth::user();
        $esAdmin = $this->esAdmin();
        $usuarioId = $usuario->id;

        try {
            $fechaSeleccionada = Carbon::parse($fecha)->startOfDay();
        } catch (\Exception $e) {
            abort(404, 'Fecha no válida.');
        }

        // ✅ TODOS ven TODAS las citas confirmadas y completadas (sin filtro por feligres)
        $citas = Cita::with(['feligres', 'sacerdote'])
            ->whereDate('fecha', $fechaSeleccionada->format('Y-m-d'))
            ->whereIn('estado', ['confirmada', 'completada'])
            ->orderBy('hora', 'asc')
            ->get()
            ->map(function ($cita) use ($esAdmin, $usuarioId) {
                $horaInicio = Carbon::parse($cita->hora);
                $duracion = $this->duracionEstimadaPorTipo($cita->tipo);
                $horaFin = $horaInicio->copy()->addMinutes($duracion);

                $cita->hora_inicio_formateada = $horaInicio->format('g:i A');
                $cita->hora_fin_formateada = $horaFin->format('g:i A');
                $cita->duracion_minutos = $duracion;
                $cita->color_barra = $this->colorPorTipo($cita->tipo);
                
                // Tipo texto
                $cita->tipo_texto = match($cita->tipo) {
                    'confesion' => 'Confesión',
                    'bautismo' => 'Bautismo',
                    'matrimonio' => 'Matrimonio',
                    'orientacion' => 'Orientación',
                    default => ucfirst($cita->tipo),
                };
                
                // Badge de estado
                $cita->estado_badge = match($cita->estado) {
                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                    'confirmada' => 'bg-green-100 text-green-800',
                    'completada' => 'bg-blue-100 text-blue-800',
                    'cancelada' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-100 text-gray-800',
                };
                
                // ✅ Flag para saber si la cita pertenece al usuario actual
                $cita->es_propia = ($cita->feligres_id === $usuarioId);
                
                // ✅ Guardar nombre del feligrés (para admin o para citas propias)
                $cita->feligres_nombre = $cita->feligres->nombre_completo ?? $cita->feligres->name ?? 'No especificado';

                return $cita;
            });

        $horas = [];

        for ($hora = 0; $hora < 24; $hora++) {
            $horas[] = [
                'valor_24' => $hora,
                'label' => Carbon::createFromTime($hora, 0)->format('g:i A'),
            ];
        }

        $fechaAnterior = $fechaSeleccionada->copy()->subDay();
        $fechaSiguiente = $fechaSeleccionada->copy()->addDay();

        return view('horarios.dia', compact(
            'fechaSeleccionada',
            'fechaAnterior',
            'fechaSiguiente',
            'citas',
            'horas',
            'esAdmin'
        ));
    }
}