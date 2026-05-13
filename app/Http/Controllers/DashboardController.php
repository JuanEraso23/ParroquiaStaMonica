<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Peticion;
use App\Models\Intencion;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Determina si el usuario autenticado es administrador.
     */
    private function esAdmin(): bool
    {
        return Auth::check() && Auth::user()->esAdministrador();
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();
        $esAdmin = $this->esAdmin();

        // Consultas base
        $citasQuery = Cita::query();
        $peticionesQuery = Peticion::query();
        $intencionesQuery = Intencion::query();

        // Si no es admin, solo se consulta información del usuario autenticado
        if (!$esAdmin) {
            $citasQuery->where('feligres_id', $usuario->id);
            $peticionesQuery->where('feligres_id', $usuario->id);
            $intencionesQuery->where('feligres_id', $usuario->id);
        }

        // Estadísticas de citas
        $citasHoy = (clone $citasQuery)
            ->whereDate('fecha', now())
            ->count();

        $citasConfirmadas = (clone $citasQuery)
            ->where('estado', 'confirmada')
            ->count();

        // Estadísticas de peticiones e intenciones
        $totalPeticiones = (clone $peticionesQuery)
            ->count();

        $intencionesPendientes = (clone $intencionesQuery)
            ->where('estado', 'pendiente')
            ->count();

        // Próximas citas
        $proximasCitas = (clone $citasQuery)
            ->with(['feligres', 'sacerdote'])
            ->whereDate('fecha', '>=', now())
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->take(5)
            ->get();

        // Peticiones recientes
        $peticionesRecientes = (clone $peticionesQuery)
            ->with(['feligres', 'sacerdote'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Intenciones recientes
        $intencionesRecientes = (clone $intencionesQuery)
            ->with(['feligres', 'sacerdote'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'esAdmin',
            'usuario',
            'citasHoy',
            'citasConfirmadas',
            'totalPeticiones',
            'intencionesPendientes',
            'proximasCitas',
            'peticionesRecientes',
            'intencionesRecientes'
        ));
    }
}
