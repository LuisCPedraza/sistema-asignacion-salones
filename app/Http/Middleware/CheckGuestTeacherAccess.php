<?php

namespace App\Http\Middleware;

use App\Modules\Auth\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckGuestTeacherAccess
{
    /**
     * Máxima duración permitida para acceso de profesor invitado (en días)
     */
    private const MAX_ACCESS_DURATION_DAYS = 365;

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

        $teacher = $user->teacher;

        // Verificar expiración en tabla users
        if ($user->temporary_access_expires_at && now() > $user->temporary_access_expires_at) {
            $this->logFailedAccess($user, 'Acceso temporal expirado en tabla users', $request);
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Su acceso temporal como profesor invitado ha expirado. Contacte al coordinador.');
        }

        // Verificar expiración en tabla teachers
        if ($teacher && $teacher->is_guest && $teacher->access_expires_at && now() > $teacher->access_expires_at) {
            $this->logFailedAccess($user, 'Acceso temporal expirado en tabla teachers', $request);
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Su acceso temporal como profesor invitado ha expirado. Contacte al coordinador.');
        }

        // Validar que no exceda duración máxima permitida
        if ($teacher && $teacher->is_guest && $teacher->created_at) {
            $daysCreated = $teacher->created_at->diffInDays(now());
            if ($daysCreated > self::MAX_ACCESS_DURATION_DAYS) {
                $this->logFailedAccess($user, 'Duración máxima de acceso excedida', $request);
                auth()->logout();

                return redirect()->route('login')
                    ->with('error', 'Su acceso como profesor invitado ha excedido la duración máxima permitida.');
            }
        }

        // Validar IP de origen si está configurada
        if ($teacher && $teacher->ip_address_allowed && !$this->isIpAllowed($request, $teacher->ip_address_allowed)) {
            $this->logFailedAccess($user, "Acceso denegado: IP no autorizada ({$request->ip()})", $request);
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Su acceso está restringido a una ubicación específica. IP actual no autorizada.');
        }

        return $next($request);
    }

    /**
     * Verificar si la IP actual está en la lista de IPs permitidas
     */
    private function isIpAllowed(Request $request, ?string $allowedIps): bool
    {
        if (!$allowedIps) {
            return true;
        }

        $currentIp = $request->ip();
        $allowedList = array_map('trim', explode(',', $allowedIps));

        foreach ($allowedList as $allowed) {
            // Soporte para wildcards simples (ej: 192.168.1.*)
            $pattern = str_replace('*', '.*', preg_quote($allowed, '/'));
            if (preg_match("/^{$pattern}$/", $currentIp)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registrar intento fallido de acceso
     */
    private function logFailedAccess($user, string $reason, Request $request): void
    {
        Log::warning('Guest teacher access denied', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'reason' => $reason,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'timestamp' => now(),
        ]);

        // Registrar en auditoría si existe
        if (method_exists($user, 'auditLogs')) {
            $user->auditLogs()->create([
                'action' => 'guest_access_denied',
                'reason' => $reason,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'performed_by' => null,
            ]);
        }
    }
}
