<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Determina si el usuario autenticado es feligrés.
     */
    private function esFeligres(): bool
    {
        return Auth::check() && Auth::user()->rol === 'feligres';
    }

    /**
     * Bloquea el acceso al perfil si no es feligrés.
     */
    private function validarAccesoPerfil(): void
    {
        if (!$this->esFeligres()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
    }

    /**
     * Muestra la vista principal del perfil del usuario.
     */
    public function index(Request $request): View
    {
        $this->validarAccesoPerfil();

        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Muestra el formulario de edición del perfil.
     */
    public function edit(Request $request): View
    {
        $this->validarAccesoPerfil();

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza únicamente la información personal del usuario autenticado.
     */
    public function update(Request $request): RedirectResponse
    {
        $this->validarAccesoPerfil();

        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'documento')->ignore($user->id),
            ],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.index')
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Muestra el formulario de cambio de contraseña.
     */
    public function password(Request $request): View
    {
        $this->validarAccesoPerfil();

        return view('profile.password');
    }

    /**
     * Actualiza la contraseña del usuario autenticado.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $this->validarAccesoPerfil();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        $user->password = Hash::make($request->password);
        $user->save();

        /**
         * Opcional pero recomendado:
         * cerrar otras sesiones al cambiar contraseña
         */
        Auth::logoutOtherDevices($request->password);

        return Redirect::route('profile.index')
            ->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Eliminar cuenta desde perfil no está permitido en este sistema.
     */
    public function destroy(Request $request): RedirectResponse
    {
        abort(403, 'No está permitido eliminar la cuenta desde el perfil.');
    }
}