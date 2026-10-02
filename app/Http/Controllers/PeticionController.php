<?php

namespace App\Http\Controllers;

use App\Models\CategoriaPeticion;
use App\Models\Peticion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeticionController extends Controller
{
    /**
     * Determina si el usuario autenticado es administrador.
     */
    private function esAdmin(): bool
    {
        return Auth::check() && Auth::user()->esAdministrador();
    }

    /**
     * Lista las peticiones.
     * El administrador ve todas y el feligrés solo las propias.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $esAdmin = $this->esAdmin();

        $estado = $request->get('estado');
        $search = $request->get('search');
        $sacerdoteId = $request->get('sacerdote_id');
        $categoriaId = $request->get('categoria_peticion_id');

        $peticionesQuery = Peticion::with([
            'feligres',
            'sacerdote',
            'categoria',
        ]);

        if (!$esAdmin) {
            $peticionesQuery->where('feligres_id', $usuario->id);
        }

        if ($estado && $estado !== 'todos') {
            $peticionesQuery->where('estado', $estado);
        }

        if ($sacerdoteId && $sacerdoteId !== 'todos') {
            $peticionesQuery->where('sacerdote_id', $sacerdoteId);
        }

        if ($categoriaId && $categoriaId !== 'todos') {
            $peticionesQuery->where(
                'categoria_peticion_id',
                $categoriaId
            );
        }

        if ($search) {
            $peticionesQuery->where(function ($query) use ($search, $esAdmin) {
                $query->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%")
                    ->orWhereHas('sacerdote', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%");
                    })
                    ->orWhereHas('categoria', function ($subQuery) use ($search) {
                        $subQuery->where('nombre', 'like', "%{$search}%");
                    });

                if ($esAdmin) {
                    $query->orWhereHas('feligres', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%")
                            ->orWhere('telefono', 'like', "%{$search}%")
                            ->orWhere('documento', 'like', "%{$search}%");
                    });
                }
            });
        }

        $items = $peticionesQuery
            ->orderBy('fecha', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        $categorias = CategoriaPeticion::orderBy('nombre')->get();

        return view('peticiones.index', compact(
            'items',
            'sacerdotes',
            'categorias',
            'estado',
            'search',
            'sacerdoteId',
            'categoriaId',
            'esAdmin'
        ));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
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

        $categorias = CategoriaPeticion::orderBy('nombre')->get();

        return view('peticiones.create', compact(
            'feligreses',
            'sacerdotes',
            'categorias'
        ));
    }

    /**
     * Guarda una petición.
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();

        $rules = [
            'sacerdote_id' => 'nullable|exists:users,id',
            'categoria_peticion_id' => 'required|exists:categoria_peticions,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'fecha' => 'required|date',
        ];

        if ($this->esAdmin()) {
            $rules['feligres_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        if (!$this->esAdmin()) {
            $validated['feligres_id'] = $usuario->id;
        }

        $validated['estado'] = 'pendiente';

        Peticion::create($validated);

        return redirect()
            ->route('peticiones.index')
            ->with('success', 'Petición creada exitosamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Peticion $peticione)
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

        $categorias = CategoriaPeticion::orderBy('nombre')->get();

        return view('peticiones.edit', compact(
            'peticione',
            'feligreses',
            'sacerdotes',
            'categorias'
        ));
    }

    /**
     * Actualiza una petición.
     */
    public function update(Request $request, Peticion $peticione)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $validated = $request->validate([
            'feligres_id' => 'required|exists:users,id',
            'sacerdote_id' => 'nullable|exists:users,id',
            'categoria_peticion_id' => 'required|exists:categoria_peticions,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,aprobada,completada,rechazada',
            'respuesta' => 'nullable|string',
        ]);

        $peticione->update($validated);

        return redirect()
            ->route('peticiones.index')
            ->with('success', 'Petición actualizada exitosamente.');
    }

    /**
     * Elimina una petición.
     */
    public function destroy(Peticion $peticione)
    {
        $usuario = Auth::user();

        if ($this->esAdmin()) {
            $peticione->delete();

            return redirect()
                ->route('peticiones.index')
                ->with('success', 'Petición eliminada exitosamente.');
        }

        if ($peticione->feligres_id !== $usuario->id) {
            abort(403, 'No tienes permiso para eliminar esta petición.');
        }

        if ($peticione->estado !== 'pendiente') {
            return redirect()
                ->route('peticiones.index')
                ->with(
                    'error',
                    'Solo puedes eliminar peticiones en estado pendiente.'
                );
        }

        $peticione->delete();

        return redirect()
            ->route('peticiones.index')
            ->with(
                'success',
                'Tu petición pendiente fue eliminada exitosamente.'
            );
    }

    /**
     * Cambia el estado de una petición.
     */
    public function cambiarEstado(Request $request, Peticion $peticione)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,aprobada,completada,rechazada',
        ]);

        $peticione->update([
            'estado' => $validated['estado'],
        ]);

        return back()->with('success', 'Estado actualizado exitosamente.');
    }
}