<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Cargar la relación role si no está cargada
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        // Verificar que el usuario tenga rol y sea administrador
        if (!$user->role) {
            abort(403, 'Usuario sin rol asignado.');
        }

        // Verificar el slug del rol
        if ($user->role->slug !== 'administrador') {
            abort(403, 'Acceso denegado: Solo para administradores.');
        }

        return $next($request);
    }
}