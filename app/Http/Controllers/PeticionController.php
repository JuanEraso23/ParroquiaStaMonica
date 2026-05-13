<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        // Siempre redirige a la vista unificada
        return redirect()->route('peticiones_intenciones.index', ['tipo' => 'peticion']);
    }

    public function create()
    {
        $usuario = Auth::user();

        if ($this->esAdmin()) {
            $feligreses = User::where('rol', 'feligres')
                ->orderBy('name')
                ->get();
        } else {
            // Feligrés solo puede crear para sí mismo
            $feligreses = User::where('id', $usuario->id)->get();
        }

        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        return view('peticiones.create', compact('feligreses', 'sacerdotes'));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $rules = [
            'sacerdote_id' => 'nullable|exists:users,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'fecha' => 'required|date',
        ];

        if ($this->esAdmin()) {
            $rules['feligres_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Forzar feligrés si no es admin
        if (!$this->esAdmin()) {
            $validated['feligres_id'] = $usuario->id;
        }

        $validated['estado'] = 'pendiente';

        Peticion::create($validated);

        return redirect()
            ->route('peticiones_intenciones.index', ['tipo' => 'peticion'])
            ->with('success', 'Petición creada exitosamente.');
    }

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

        return view('peticiones.edit', compact('peticione', 'feligreses', 'sacerdotes'));
    }

    public function update(Request $request, Peticion $peticione)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $validated = $request->validate([
            'feligres_id' => 'required|exists:users,id',
            'sacerdote_id' => 'nullable|exists:users,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,aprobada,completada,rechazada',
            'respuesta' => 'nullable|string',
        ]);

        $peticione->update($validated);

        return redirect()
            ->route('peticiones_intenciones.index', ['tipo' => 'peticion'])
            ->with('success', 'Petición actualizada exitosamente.');
    }

    public function destroy(Peticion $peticione)
    {
        $usuario = Auth::user();

        if ($this->esAdmin()) {
            $peticione->delete();

            return redirect()
                ->route('peticiones_intenciones.index', ['tipo' => 'peticion'])
                ->with('success', 'Petición eliminada exitosamente.');
        }

        // Feligrés: solo puede eliminar sus peticiones pendientes
        if ($peticione->feligres_id !== $usuario->id) {
            abort(403, 'No tienes permiso para eliminar esta petición.');
        }

        if ($peticione->estado !== 'pendiente') {
            return redirect()
                ->route('peticiones_intenciones.index', ['tipo' => 'peticion'])
                ->with('error', 'Solo puedes eliminar peticiones en estado pendiente.');
        }

        $peticione->delete();

        return redirect()
            ->route('peticiones_intenciones.index', ['tipo' => 'peticion'])
            ->with('success', 'Tu petición pendiente fue eliminada exitosamente.');
    }

    public function cambiarEstado(Request $request, Peticion $peticione)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'estado' => 'required|in:pendiente,aprobada,completada,rechazada',
        ]);

        $peticione->update([
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Estado actualizado exitosamente.');
    }
}