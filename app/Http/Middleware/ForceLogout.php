<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class ForceLogout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $now = Carbon::now();

            /**
             * Si el usuario NO está autenticado por remember me,
             * aplicamos expiración por inactividad.
             */
            if (!Auth::viaRemember()) {

                if (!$user->last_activity) {
                    $user->update(['last_activity' => $now]);
                    return $next($request);
                }

                $lastActivity = $user->last_activity instanceof Carbon
                    ? $user->last_activity
                    : Carbon::parse($user->last_activity);

                $inactiveMinutes = $lastActivity->diffInMinutes($now);

                if ($inactiveMinutes > 5) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $user->update(['last_activity' => null]);

                    return redirect('/login');
                }
            }

            /**
             * Siempre actualizamos la última actividad
             */
            $user->update(['last_activity' => $now]);
        }

        return $next($request);
    }
}
