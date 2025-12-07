<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoordinatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('coordinador')) {
            abort(403, 'Acceso denegado: Solo para coordinadores.');
        }

        // NO redirigir aquí si es post-login (evita bucle); solo check
        return $next($request);
    }
}
