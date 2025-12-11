<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendGuestTeacherExpirationNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guest-teachers:send-expiration-notifications {--days=7 : Notificar si faltan N días para expiración}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar notificaciones a profesores invitados próximos a expiración';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        
        // Buscar profesores invitados que expiran en los próximos N días
        $expiringTeachers = Teacher::where('is_guest', true)
            ->whereNotNull('access_expires_at')
            ->whereBetween('access_expires_at', [
                now(),
                now()->addDays($days),
            ])
            ->with('user')
            ->get();

        if ($expiringTeachers->isEmpty()) {
            $this->info('No hay profesores invitados próximos a expiración.');
            return self::SUCCESS;
        }

        $notificationsSent = 0;

        foreach ($expiringTeachers as $teacher) {
            if ($teacher->user) {
                try {
                    // Enviar notificación por email
                    $teacher->user->notify(new \App\Notifications\GuestTeacherExpirationNotification($teacher));
                    $notificationsSent++;

                    Log::info('Guest teacher expiration notification sent', [
                        'teacher_id' => $teacher->id,
                        'user_id' => $teacher->user->id,
                        'expires_at' => $teacher->access_expires_at,
                        'days_remaining' => $teacher->access_expires_at->diffInDays(now()),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send guest teacher notification', [
                        'teacher_id' => $teacher->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("✅ Se enviaron {$notificationsSent} notificaciones de expiración.");
        return self::SUCCESS;
    }
}
