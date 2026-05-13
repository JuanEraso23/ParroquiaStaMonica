<?php

namespace App\Http\Controllers;

use App\Models\Peticion;
use App\Models\Intencion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class PeticionIntencionController extends Controller
{
    /**
     * Determina si el usuario autenticado es administrador.
     */
    private function esAdmin(): bool
    {
        return Auth::check() && Auth::user()->esAdministrador();
    }

    /**
     * Vista unificada de peticiones e intenciones.
     * Admin ve todo, feligrés ve solo sus registros.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $esAdmin = $this->esAdmin();

        $tipo = $request->get('tipo', 'todos');
        $estado = $request->get('estado');
        $search = $request->get('search');
        $sacerdoteId = $request->get('sacerdote_id');

        // Consultas base
        $peticionesQuery = Peticion::with(['feligres', 'sacerdote']);
        $intencionesQuery = Intencion::with(['feligres', 'sacerdote']);

        // Si no es admin, solo ve sus propios registros
        if (!$esAdmin) {
            $peticionesQuery->where('feligres_id', $usuario->id);
            $intencionesQuery->where('feligres_id', $usuario->id);
        }

        // Filtro por estado
        if ($estado && $estado !== 'todos') {
            $peticionesQuery->where('estado', $estado);
            $intencionesQuery->where('estado', $estado);
        }

        // Filtro por sacerdote
        if ($sacerdoteId && $sacerdoteId !== 'todos') {
            $peticionesQuery->where('sacerdote_id', $sacerdoteId);
            $intencionesQuery->where('sacerdote_id', $sacerdoteId);
        }

        // Búsqueda según rol
        if ($search) {
            if ($esAdmin) {
                $peticionesQuery->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%")
                        ->orWhereHas('feligres', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%")
                                ->orWhere('telefono', 'like', "%{$search}%")
                                ->orWhere('documento', 'like', "%{$search}%");
                        })
                        ->orWhereHas('sacerdote', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%");
                        });
                });

                $intencionesQuery->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%")
                        ->orWhere('nombre_difunto', 'like', "%{$search}%")
                        ->orWhereHas('feligres', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%")
                                ->orWhere('telefono', 'like', "%{$search}%")
                                ->orWhere('documento', 'like', "%{$search}%");
                        })
                        ->orWhereHas('sacerdote', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%");
                        });
                });
            } else {
                $peticionesQuery->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%")
                        ->orWhereHas('sacerdote', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%");
                        });
                });

                $intencionesQuery->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%")
                        ->orWhere('nombre_difunto', 'like', "%{$search}%")
                        ->orWhereHas('sacerdote', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%");
                        });
                });
            }
        }

        // Mostrar solo peticiones
        if ($tipo === 'peticion') {
            $items = $peticionesQuery
                ->orderBy('fecha', 'desc')
                ->paginate(15);

            $items->each(function ($item) {
                $item->tipo_display = 'Petición';
                $item->tipo_class = 'bg-blue-100 text-blue-800';
                $item->modelo_origen = 'peticion';
            });

        // Mostrar solo intenciones
        } elseif ($tipo === 'intencion') {
            $items = $intencionesQuery
                ->orderBy('fecha', 'desc')
                ->paginate(15);

            $items->each(function ($item) {
                $item->tipo_display = 'Intención';
                $item->tipo_class = 'bg-purple-100 text-purple-800';
                $item->modelo_origen = 'intencion';
            });

        // Mostrar ambos tipos
        } else {
            $peticiones = $peticionesQuery
                ->orderBy('fecha', 'desc')
                ->get();

            $intenciones = $intencionesQuery
                ->orderBy('fecha', 'desc')
                ->get();

            $peticiones->each(function ($item) {
                $item->tipo_display = 'Petición';
                $item->tipo_class = 'bg-blue-100 text-blue-800';
                $item->modelo_origen = 'peticion';
            });

            $intenciones->each(function ($item) {
                $item->tipo_display = 'Intención';
                $item->tipo_class = 'bg-purple-100 text-purple-800';
                $item->modelo_origen = 'intencion';
            });

            $coleccion = $peticiones
                ->concat($intenciones)
                ->sortByDesc('fecha')
                ->values();

            $page = $request->get('page', 1);
            $perPage = 15;

            $items = new LengthAwarePaginator(
                $coleccion->forPage($page, $perPage),
                $coleccion->count(),
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );
        }

        $items->appends($request->all());

        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        return view('peticiones_intenciones.index', compact(
            'items',
            'sacerdotes',
            'tipo',
            'estado',
            'search',
            'sacerdoteId',
            'esAdmin'
        ));
    }
}