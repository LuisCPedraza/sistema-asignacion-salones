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
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRoleSlug = auth()->user()->role?->slug;

        if (!$userRoleSlug || !in_array($userRoleSlug, $roles)) {
            abort(403, 'Acceso denegado. Tu rol no tiene permiso para esta sección.');
        }

        return $next($request);
    }
}