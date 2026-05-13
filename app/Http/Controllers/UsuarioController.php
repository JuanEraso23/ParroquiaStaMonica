<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar que solo administradores accedan
        if (!Auth::user()->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        // Usuarios por rol para las tarjetas del prototipo
        $administradores = User::whereIn('rol', ['parroco', 'vicario'])
                        ->orderBy('name')
                        ->get();
        
        $secretarias = User::where('rol', 'secretaria')
                        ->orderBy('name')
                        ->get();
        
        $feligreses = User::where('rol', 'feligres')
                        ->orderBy('name')
                        ->get();
        
        // Para la paginación (opcional, si quieres mantener la tabla original)
        $usuarios = User::orderBy('rol')->orderBy('name')->paginate(15);
        
        return view('usuarios.index', compact('usuarios', 'administradores', 'secretarias', 'feligreses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'documento' => 'required|string|max:20|unique:users',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'email' => 'required|email|unique:users',
            'rol' => 'required|in:feligres,secretaria,parroco,vicario',
            'cargo' => 'nullable|string|max:100',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['activo'] = true;
        
        User::create($validated);
        
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        // Verificar permisos: solo administradores o el propio usuario
        if (!Auth::user()->esAdministrador() && Auth::id() !== $usuario->id) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }
        
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        if (!Auth::user()->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        if (!Auth::user()->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'documento' => 'required|string|max:20|unique:users,documento,' . $usuario->id,
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:feligres,secretaria,parroco,vicario',
            'cargo' => 'nullable|string|max:100',
            'activo' => 'boolean',
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }
        
        $usuario->update($validated);
        
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        if (!Auth::user()->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        // No permitir eliminar el propio usuario
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }
        
        $usuario->delete();
        
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
    
    /**
     * Toggle user active status
     */
    public function toggleActivo(User $usuario)
    {
        if (!Auth::user()->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        // No permitir desactivar el propio usuario
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }
        
        $usuario->activo = !$usuario->activo;
        $usuario->save();
        
        $estado = $usuario->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$estado} exitosamente.");
    }
}