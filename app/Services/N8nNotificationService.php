<?php

namespace App\Services;

use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Asignacion\Models\Assignment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class N8nNotificationService
{
    /**
     * Obtener asignaciones del día siguiente para un profesor
     */
    public function getNextDayAssignments(Teacher $teacher): array
    {
        $tomorrow = now()->addDay();
        $dayName = strtolower($tomorrow->format('l'));
        
        // Mapear día en inglés
        $dayMap = [
            'monday' => 'monday',
            'tuesday' => 'tuesday',
            'wednesday' => 'wednesday',
            'thursday' => 'thursday',
            'friday' => 'friday',
            'saturday' => 'saturday',
            'sunday' => 'sunday',
        ];

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->where('day', $dayMap[strtolower($tomorrow->format('l'))] ?? 'monday')
            ->with(['group', 'subject', 'classroom'])
            ->get();

        return $assignments->map(fn($a) => [
            'subject' => $a->subject?->name,
            'group' => $a->group?->name,
            'classroom' => $a->classroom?->name,
            'start_time' => $a->start_time,
            'end_time' => $a->end_time,
            'day' => $tomorrow->format('d/m/Y'),
        ])->toArray();
    }

    /**
     * Obtener profesores invitados con acceso próximo a expirar (7 días)
     */
    public function getExpiringSoonGuests(): array
    {
        $inSevenDays = now()->addDays(7);
        
        return Teacher::where('is_guest', true)
            ->whereNotNull('access_expires_at')
            ->where('access_expires_at', '<=', $inSevenDays)
            ->where('access_expires_at', '>', now())
            ->with('user')
            ->get()
            ->map(fn($t) => [
                'email' => $t->user?->email,
                'name' => $t->user?->name,
                'expires_at' => $t->access_expires_at->format('d/m/Y H:i'),
                'days_left' => $t->access_expires_at->diffInDays(now()),
            ])
            ->toArray();
    }

    /**
     * Obtener resumen de conflictos para administrador
     */
    public function getConflictsSummaryForAdmin(): array
    {
        $assignments = Assignment::with(['group', 'teacher', 'classroom'])->get();
        $conflicts = [];

        foreach ($assignments as $assignment) {
            // Verificar conflicto de profesor (dos asignaciones solapadas)
            foreach ($assignments as $other) {
                if ($other->id === $assignment->id) {
                    continue;
                }
                
                if ($other->teacher_id !== $assignment->teacher_id) {
                    continue;
                }
                
                if ($other->day !== $assignment->day) {
                    continue;
                }
                
                // Verificar solapamiento de horarios
                $startTime1 = Carbon::parse($assignment->start_time);
                $endTime1 = Carbon::parse($assignment->end_time);
                $startTime2 = Carbon::parse($other->start_time);
                $endTime2 = Carbon::parse($other->end_time);
                
                $overlaps = ($startTime1 < $endTime2) && ($startTime2 < $endTime1);
                
                if ($overlaps) {
                    // Evitar duplicados (solo agregar si este assignment tiene menor ID)
                    if ($assignment->id < $other->id) {
                        $conflicts[] = [
                            'type' => 'teacher',
                            'description' => "Profesor {$assignment->teacher?->full_name} solapado",
                            'group1' => $assignment->group?->name,
                            'group2' => $other->group?->name,
                            'day' => $assignment->day,
                        ];
                    }
                }
            }
        }

        return [
            'total_conflicts' => count($conflicts),
            'conflicts' => $conflicts,
        ];
    }

    /**
     * Enviar correo a profesor con asignaciones del día siguiente
     */
    public function sendTeacherDailyAssignment(Teacher $teacher): void
    {
        try {
            $assignments = $this->getNextDayAssignments($teacher);

            if (empty($assignments)) {
                Log::info("No hay asignaciones para {$teacher->user?->email} mañana");
                return;
            }

            Log::info("Enviando correo a {$teacher->user?->email}", [
                'assignments_count' => count($assignments),
            ]);

            // TODO: Implementar Mail::send() o usar notificación
            // Por ahora solo log
        } catch (\Exception $e) {
            Log::error("Error enviando correo a profesor: {$e->getMessage()}");
        }
    }

    /**
     * Enviar resumen de conflictos al administrador
     */
    public function sendConflictsSummaryToAdmin(): void
    {
        try {
            $summary = $this->getConflictsSummaryForAdmin();

            if ($summary['total_conflicts'] === 0) {
                Log::info("No hay conflictos para reportar");
                return;
            }

            Log::info("Conflictos detectados: {$summary['total_conflicts']}");

            // TODO: Implementar envío de correo a admin
        } catch (\Exception $e) {
            Log::error("Error en resumen de conflictos: {$e->getMessage()}");
        }
    }

    /**
     * Enviar aviso a profesor invitado sobre expiración próxima
     */
    public function sendGuestExpirationWarning(Teacher $teacher): void
    {
        try {
            $expiresAt = $teacher->access_expires_at->format('d/m/Y H:i');
            $daysLeft = $teacher->access_expires_at->diffInDays(now());

            Log::info("Aviso de expiración para {$teacher->user?->email}", [
                'days_left' => $daysLeft,
                'expires_at' => $expiresAt,
            ]);

            // TODO: Implementar envío de correo de aviso
        } catch (\Exception $e) {
            Log::error("Error enviando aviso de expiración: {$e->getMessage()}");
        }
    }
}
