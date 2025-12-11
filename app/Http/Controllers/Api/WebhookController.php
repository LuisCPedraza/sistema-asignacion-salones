<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WebhookController extends Controller
{
    /**
     * Notifica a n8n cuando se crea una nueva asignación
     * 
     * @param \App\Modules\Asignacion\Models\Assignment $assignment
     * @return void
     */
    public static function notifyAssignmentCreated($assignment)
    {
        $webhookUrl = config('webhooks.n8n_assignment_created');
        
        if (!$webhookUrl) {
            Log::info('Webhook URL not configured for assignment.created');
            return;
        }

        try {
            // Cargar relaciones necesarias
            $assignment->load(['teacher', 'group', 'classroom', 'subject']);
            
            $payload = [
                'event' => 'assignment.created',
                'assignment_id' => $assignment->id,
                'teacher_id' => $assignment->teacher_id,
                'teacher_name' => $assignment->teacher ? 
                    ($assignment->teacher->first_name . ' ' . $assignment->teacher->last_name) : 
                    'N/A',
                'teacher_email' => $assignment->teacher->email ?? null,
                'group_id' => $assignment->student_group_id,
                'group_name' => $assignment->group->name ?? 'N/A',
                'classroom_id' => $assignment->classroom_id,
                'classroom_name' => $assignment->classroom->name ?? 'N/A',
                'classroom_capacity' => $assignment->classroom->capacity ?? null,
                'classroom_floor' => $assignment->classroom->floor ?? null,
                'subject_id' => $assignment->subject_id,
                'subject_name' => $assignment->subject->name ?? 'N/A',
                'day' => $assignment->day,
                'start_time' => $assignment->start_time,
                'end_time' => $assignment->end_time,
                'score' => $assignment->score,
                'assigned_by_algorithm' => $assignment->assigned_by_algorithm,
                'timestamp' => now()->toIso8601String(),
            ];

            $response = Http::timeout(10)
                ->retry(3, 100)
                ->post($webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Webhook sent successfully', [
                    'event' => 'assignment.created',
                    'assignment_id' => $assignment->id,
                    'status' => $response->status()
                ]);

                // Registrar en auditoría
                self::logWebhookEvent('assignment.created', $assignment->id, 'Assignment', $payload);
            } else {
                Log::error('Webhook failed', [
                    'event' => 'assignment.created',
                    'assignment_id' => $assignment->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

        } catch (Exception $e) {
            Log::error('Exception sending webhook', [
                'event' => 'assignment.created',
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Notifica a n8n cuando se actualiza una asignación
     * 
     * @param \App\Modules\Asignacion\Models\Assignment $oldAssignment
     * @param \App\Modules\Asignacion\Models\Assignment $newAssignment
     * @return void
     */
    public static function notifyAssignmentUpdated($oldAssignment, $newAssignment)
    {
        $webhookUrl = config('webhooks.n8n_assignment_updated');
        
        if (!$webhookUrl) {
            Log::info('Webhook URL not configured for assignment.updated');
            return;
        }

        try {
            // Cargar relaciones necesarias
            $newAssignment->load(['teacher', 'group', 'classroom', 'subject']);
            
            // Detectar cambios
            $changes = [];
            
            if ($oldAssignment->classroom_id !== $newAssignment->classroom_id) {
                $changes['classroom'] = [
                    'old' => [
                        'id' => $oldAssignment->classroom_id,
                        'name' => $oldAssignment->classroom->name ?? 'N/A'
                    ],
                    'new' => [
                        'id' => $newAssignment->classroom_id,
                        'name' => $newAssignment->classroom->name ?? 'N/A'
                    ]
                ];
            }
            
            if ($oldAssignment->teacher_id !== $newAssignment->teacher_id) {
                $changes['teacher'] = [
                    'old' => [
                        'id' => $oldAssignment->teacher_id,
                        'name' => $oldAssignment->teacher ? 
                            ($oldAssignment->teacher->first_name . ' ' . $oldAssignment->teacher->last_name) : 
                            'N/A'
                    ],
                    'new' => [
                        'id' => $newAssignment->teacher_id,
                        'name' => $newAssignment->teacher ? 
                            ($newAssignment->teacher->first_name . ' ' . $newAssignment->teacher->last_name) : 
                            'N/A',
                        'email' => $newAssignment->teacher->email ?? null
                    ]
                ];
            }
            
            if ($oldAssignment->day !== $newAssignment->day || 
                $oldAssignment->start_time !== $newAssignment->start_time ||
                $oldAssignment->end_time !== $newAssignment->end_time) {
                $changes['schedule'] = [
                    'old' => [
                        'day' => $oldAssignment->day,
                        'start_time' => $oldAssignment->start_time,
                        'end_time' => $oldAssignment->end_time
                    ],
                    'new' => [
                        'day' => $newAssignment->day,
                        'start_time' => $newAssignment->start_time,
                        'end_time' => $newAssignment->end_time
                    ]
                ];
            }

            $payload = [
                'event' => 'assignment.updated',
                'assignment_id' => $newAssignment->id,
                'group_id' => $newAssignment->student_group_id,
                'group_name' => $newAssignment->group->name ?? 'N/A',
                'teacher_id' => $newAssignment->teacher_id,
                'teacher_name' => $newAssignment->teacher ? 
                    ($newAssignment->teacher->first_name . ' ' . $newAssignment->teacher->last_name) : 
                    'N/A',
                'teacher_email' => $newAssignment->teacher->email ?? null,
                'changes' => $changes,
                'timestamp' => now()->toIso8601String(),
            ];

            $response = Http::timeout(10)
                ->retry(3, 100)
                ->post($webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Webhook sent successfully', [
                    'event' => 'assignment.updated',
                    'assignment_id' => $newAssignment->id,
                    'status' => $response->status()
                ]);

                // Registrar en auditoría
                self::logWebhookEvent('assignment.updated', $newAssignment->id, 'Assignment', $payload);
            } else {
                Log::error('Webhook failed', [
                    'event' => 'assignment.updated',
                    'assignment_id' => $newAssignment->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

        } catch (Exception $e) {
            Log::error('Exception sending webhook', [
                'event' => 'assignment.updated',
                'assignment_id' => $newAssignment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Notifica cuando se detectan conflictos horarios
     * 
     * @param array $conflicts
     * @return void
     */
    public static function notifyConflictsDetected($conflicts)
    {
        $webhookUrl = config('webhooks.n8n_conflicts_detected');
        
        if (!$webhookUrl) {
            Log::info('Webhook URL not configured for conflicts.detected');
            return;
        }

        try {
            $payload = [
                'event' => 'conflicts.detected',
                'total_conflicts' => count($conflicts),
                'conflicts' => $conflicts,
                'timestamp' => now()->toIso8601String(),
            ];

            $response = Http::timeout(10)
                ->retry(3, 100)
                ->post($webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Conflicts webhook sent successfully', [
                    'total_conflicts' => count($conflicts)
                ]);

                // Registrar en auditoría
                self::logWebhookEvent('conflicts.detected', null, 'System', $payload);
            }

        } catch (Exception $e) {
            Log::error('Exception sending conflicts webhook', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notifica sobre disponibilidades incompletas
     * 
     * @param array $incompleteData
     * @return void
     */
    public static function notifyIncompleteAvailabilities($incompleteData)
    {
        $webhookUrl = config('webhooks.n8n_incomplete_availabilities');
        
        if (!$webhookUrl) {
            Log::info('Webhook URL not configured for incomplete.availabilities');
            return;
        }

        try {
            $payload = [
                'event' => 'incomplete.availabilities',
                'teachers_without_availability' => $incompleteData['teachers'] ?? [],
                'classrooms_without_availability' => $incompleteData['classrooms'] ?? [],
                'timestamp' => now()->toIso8601String(),
            ];

            $response = Http::timeout(10)
                ->retry(3, 100)
                ->post($webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Incomplete availabilities webhook sent successfully');
                
                // Registrar en auditoría
                self::logWebhookEvent('incomplete.availabilities', null, 'System', $payload);
            }

        } catch (Exception $e) {
            Log::error('Exception sending incomplete availabilities webhook', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Registra el evento de webhook en la tabla de auditoría
     * 
     * @param string $event
     * @param int|null $entityId
     * @param string $entityType
     * @param array $payload
     * @return void
     */
    private static function logWebhookEvent($event, $entityId, $entityType, $payload)
    {
        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'event' => $event,
                'entity_id' => $entityId,
                'entity_type' => $entityType,
                'changes' => $payload,
                'description' => "Webhook disparado: {$event}",
                'source' => 'webhook',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to log webhook event to audit_logs', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
