<?php

namespace App\Notifications;

use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GuestTeacherExpirationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Teacher $teacher;

    /**
     * Create a new notification instance.
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysRemaining = $this->teacher->access_expires_at->diffInDays(now());
        $expiresAt = $this->teacher->access_expires_at->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('⏰ Tu acceso como profesor invitado está por expirar')
            ->line("Hola {$notifiable->name},")
            ->line("Tu acceso temporal como profesor invitado a la plataforma Sistema de Asignación de Salones expirará pronto.")
            ->line('')
            ->line("📅 **Información de tu acceso:**")
            ->line("- Fecha de expiración: {$expiresAt}")
            ->line("- Tiempo restante: {$daysRemaining} día(s)")
            ->line('')
            ->line('Si necesitas que se extienda tu acceso, contacta con el coordinador académico de tu institución.')
            ->line('')
            ->action('Acceder a la plataforma', url('/login'))
            ->line('')
            ->line('Este es un mensaje automático. Por favor, no respondas a este correo.')
            ->salutation('Saludos cordiales,<br>Sistema de Asignación de Salones');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $daysRemaining = $this->teacher->access_expires_at->diffInDays(now());

        return [
            'title' => '⏰ Tu acceso está por expirar',
            'message' => "Tu acceso como profesor invitado expirará en {$daysRemaining} día(s)",
            'type' => 'warning',
            'expires_at' => $this->teacher->access_expires_at,
            'days_remaining' => $daysRemaining,
        ];
    }
}
