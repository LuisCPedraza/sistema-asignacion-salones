<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class CheckGuestTeacherAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Solo validar si el usuario está autenticado
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Solo aplicar validación a profesores invitados
        if ($user->role_id !== Role::PROFESOR_INVITADO) {
            return $next($request);
        }

        // Verificar si el profesor tiene acceso temporal expirado
        if ($user->temporary_access_expires_at && now() > $user->temporary_access_expires_at) {
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Su acceso temporal como profesor invitado ha expirado. Contacte al coordinador.');
        }

        // Verificar también en el modelo Teacher
        $teacher = $user->teacher;
        if ($teacher && $teacher->is_guest && $teacher->access_expires_at && now() > $teacher->access_expires_at) {
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Su acceso temporal como profesor invitado ha expirado. Contacte al coordinador.');
        }

        return $next($request);
    }
}
