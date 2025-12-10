<?php

namespace App\Listeners;

use App\Events\GuestTeacherAccessChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogGuestTeacherAccessChange implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GuestTeacherAccessChanged $event): void
    {
        $action = $event->action;
        $user = $event->user;
        $performedBy = $event->performedBy;

        // Preparar descripción del cambio
        // Build a human-readable description using the old/new data snapshot
        $description = $this->getActionDescription($event->oldData, $event->newData);

        // Registrar en logs
        Log::channel('guest_teacher_access')->info("Guest teacher access {$action}", [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'action' => $action,
            'description' => $description,
            'old_data' => $event->oldData,
            'new_data' => $event->newData,
            'performed_by' => $performedBy?->id,
            'performed_by_email' => $performedBy?->email,
            'timestamp' => now(),
        ]);

        // Registrar en tabla de auditoría si existe
        if (method_exists(\App\Models\AuditLog::class, 'create')) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'performed_by_id' => $performedBy?->id,
                'action' => "guest_teacher_{$action}",
                'model_type' => \App\Models\User::class,
                'model_id' => $user->id,
                'changes' => [
                    'old' => $event->oldData,
                    'new' => $event->newData,
                ],
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        }
    }

    /**
     * Obtener descripción del cambio realizado
     */
    private function getActionDescription(?array $oldData, ?array $newData): string
    {
        $changes = [];

        if ($oldData && $newData) {
            foreach ($newData as $key => $newValue) {
                if (!isset($oldData[$key]) || $oldData[$key] != $newValue) {
                    $oldValue = $oldData[$key] ?? 'no establecido';
                    
                    if ($key === 'access_expires_at') {
                        $changes[] = "Fecha de expiración actualizada de '{$oldValue}' a '{$newValue}'";
                    } elseif ($key === 'ip_address_allowed') {
                        $changes[] = "Restricción de IP actualizada de '{$oldValue}' a '{$newValue}'";
                    } else {
                        $changes[] = "{$key}: {$oldValue} → {$newValue}";
                    }
                }
            }
        }

        return implode('; ', $changes) ?: 'Sin cambios registrados';
    }
}
