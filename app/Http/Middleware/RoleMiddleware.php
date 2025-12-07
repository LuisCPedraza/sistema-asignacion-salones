<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ejemplo de uso:
     * ->middleware('role:admin,coordinador,secretaria_coordinacion')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        \Log::info('=== ROLE MIDDLEWARE EJECUTADO ===');
        \Log::info('Roles esperados:', $roles);
        \Log::info('Usuario autenticado:', [auth()->user() ? auth()->user()->id : 'null']);
        
        if (!auth()->check()) {
            \Log::warning('Usuario no autenticado');
            return redirect()->route('login');
        }

        $userRoleSlug = auth()->user()->role?->slug;
        \Log::info('Rol del usuario:', [$userRoleSlug]);

        if (!$userRoleSlug || !in_array($userRoleSlug, $roles)) {
            \Log::warning('Acceso denegado para rol: ' . $userRoleSlug);
            abort(403, 'Acceso denegado. Tu rol no tiene permiso para esta sección.');
        }

        \Log::info('Acceso permitido');
        return $next($request);
    }
}