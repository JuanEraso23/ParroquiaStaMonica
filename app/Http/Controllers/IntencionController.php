<?php

namespace App\Http\Controllers;

use App\Models\Intencion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntencionController extends Controller
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
        return redirect()->route('peticiones_intenciones.index', ['tipo' => 'intencion']);
    }

    public function create()
    {
        $usuario = Auth::user();

        if ($this->esAdmin()) {
            $feligreses = User::where('rol', 'feligres')->orderBy('name')->get();
        } else {
            // Feligrés solo puede crear para sí mismo
            $feligreses = User::where('id', $usuario->id)->get();
        }

        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])
            ->orderBy('name')
            ->get();

        return view('intenciones.create', compact('feligreses', 'sacerdotes'));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $rules = [
            'sacerdote_id' => 'nullable|exists:users,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'fecha' => 'required|date',
            'nombre_difunto' => 'nullable|string|max:255',
            'fecha_misa' => 'nullable|date',
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

        Intencion::create($validated);

        return redirect()
            ->route('peticiones_intenciones.index', ['tipo' => 'intencion'])
            ->with('success', 'Intención creada exitosamente.');
    }

    public function edit(Intencion $intencione)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $feligreses = User::where('rol', 'feligres')->orderBy('name')->get();
        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])->orderBy('name')->get();

        return view('intenciones.edit', compact('intencione', 'feligreses', 'sacerdotes'));
    }

    public function update(Request $request, Intencion $intencione)
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
            'nombre_difunto' => 'nullable|string|max:255',
            'fecha_misa' => 'nullable|date',
            'estado' => 'required|in:pendiente,confirmada,realizada,cancelada',
            'respuesta' => 'nullable|string',
        ]);

        $intencione->update($validated);

        return redirect()
            ->route('peticiones_intenciones.index', ['tipo' => 'intencion'])
            ->with('success', 'Intención actualizada exitosamente.');
    }

    public function destroy(Intencion $intencione)
    {
        $usuario = Auth::user();

        if ($this->esAdmin()) {
            $intencione->delete();

            return redirect()
                ->route('peticiones_intenciones.index', ['tipo' => 'intencion'])
                ->with('success', 'Intención eliminada exitosamente.');
        }

        // Feligrés: solo puede eliminar sus intenciones pendientes
        if ($intencione->feligres_id !== $usuario->id) {
            abort(403, 'No tienes permiso para eliminar esta intención.');
        }

        if ($intencione->estado !== 'pendiente') {
            return redirect()
                ->route('peticiones_intenciones.index', ['tipo' => 'intencion'])
                ->with('error', 'Solo puedes eliminar intenciones en estado pendiente.');
        }

        $intencione->delete();

        return redirect()
            ->route('peticiones_intenciones.index', ['tipo' => 'intencion'])
            ->with('success', 'Tu intención pendiente fue eliminada exitosamente.');
    }

    public function cambiarEstado(Request $request, Intencion $intencione)
    {
        if (!$this->esAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,realizada,cancelada',
        ]);

        $intencione->update([
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Estado actualizado exitosamente.');
    }
}