<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no ha iniciado sesión
        if (!Auth::check()) {
            abort(403, 'Debes iniciar sesión para acceder a esta sección.');
        }

        $usuario = Auth::user();

        // Si no es administrador
        if (!$usuario->esAdministrador()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}