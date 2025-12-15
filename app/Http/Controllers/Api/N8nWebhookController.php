<?php

namespace App\Http\Controllers\Api;

use App\Services\N8nNotificationService;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class N8nWebhookController
{
    protected $notificationService;

    public function __construct(N8nNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Recibir webhook de n8n para disparar notificaciones
     * POST /api/webhooks/n8n/notify
     */
    public function notify(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Webhook n8n recibido', [
                'type' => $payload['type'] ?? 'unknown',
                'timestamp' => now(),
            ]);

            $type = $payload['type'] ?? null;

            match ($type) {
                'daily_teacher_assignments' => $this->handleDailyTeacherAssignments($payload),
                'conflict_summary' => $this->handleConflictSummary($payload),
                'guest_expiration_warning' => $this->handleGuestExpirationWarning($payload),
                default => Log::warning("Tipo de webhook desconocido: {$type}"),
            };

            return response()->json([
                'success' => true,
                'message' => "Notificación procesada: {$type}",
            ]);
        } catch (\Exception $e) {
            Log::error('Error en webhook n8n', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dispara notificaciones diarias a profesores
     */
    private function handleDailyTeacherAssignments(array $payload): void
    {
        $teachers = Teacher::where('is_active', true)
            ->where('is_guest', false)
            ->with('user')
            ->get();

        foreach ($teachers as $teacher) {
            $this->notificationService->sendTeacherDailyAssignment($teacher);
        }

        Log::info("Correos diarios enviados a {$teachers->count()} profesores");
    }

    /**
     * Dispara resumen de conflictos a administrador
     */
    private function handleConflictSummary(array $payload): void
    {
        $this->notificationService->sendConflictsSummaryToAdmin();
    }

    /**
     * Dispara aviso de expiración a profesores invitados
     */
    private function handleGuestExpirationWarning(array $payload): void
    {
        $expiringGuests = $this->notificationService->getExpiringSoonGuests();

        foreach ($expiringGuests as $guest) {
            $teacher = Teacher::whereHas('user', function ($q) use ($guest) {
                $q->where('email', $guest['email']);
            })->first();

            if ($teacher) {
                $this->notificationService->sendGuestExpirationWarning($teacher);
            }
        }

        Log::info("Avisos de expiración enviados a " . count($expiringGuests) . " invitados");
    }

    /**
     * Endpoint para consultar asignaciones del día siguiente (usado por n8n)
     * GET /api/webhooks/n8n/next-day-assignments?teacher_id=ID
     */
    public function getNextDayAssignments(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        $teacher = Teacher::find($teacherId);

        if (!$teacher) {
            return response()->json([
                'error' => 'Profesor no encontrado',
            ], 404);
        }

        $assignments = $this->notificationService->getNextDayAssignments($teacher);

        return response()->json([
            'success' => true,
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->user?->name,
                'email' => $teacher->user?->email,
            ],
            'assignments' => $assignments,
            'count' => count($assignments),
        ]);
    }

    /**
     * Endpoint para obtener conflictos (usado por n8n)
     * GET /api/webhooks/n8n/conflicts
     */
    public function getConflicts()
    {
        $summary = $this->notificationService->getConflictsSummaryForAdmin();

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * Endpoint para obtener profesores invitados con expiración próxima
     * GET /api/webhooks/n8n/expiring-guests
     */
    public function getExpiringGuests()
    {
        $guests = $this->notificationService->getExpiringSoonGuests();

        return response()->json([
            'success' => true,
            'guests' => $guests,
            'count' => count($guests),
        ]);
    }

    /**
     * Endpoint para obtener estadísticas del sistema
     * GET /api/webhooks/n8n/stats
     */
    public function getStats()
    {
        return response()->json([
            'success' => true,
            'timestamp' => now()->toIso8601String(),
            'system_healthy' => true,
            'message' => 'N8n webhook endpoint is operational',
        ]);
    }
}
