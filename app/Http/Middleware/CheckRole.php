<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            \Log::info('CheckRole: No autenticado - Redirigiendo a /login');
            return redirect('/login');
        }

        $userRole = Auth::user()->rol;
        \Log::info('CheckRole: Rol del usuario: ' . $userRole);

        if (!in_array($userRole, $roles)) {
            \Log::info('CheckRole: Rol rechazado. Requiere: ' . implode(', ', $roles));
            return response('Acceso denegado. Requiere rol: ' . implode(', ', $roles), 403);
        }

        \Log::info('CheckRole: Rol aceptado - Procediendo');
        return $next($request);
    }
}