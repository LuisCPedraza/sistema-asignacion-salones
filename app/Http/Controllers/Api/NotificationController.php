<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\GestionAcademica\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Obtener clases del día siguiente para todos los profesores
     * Endpoint para n8n
     */
    public function getTomorrowClasses(Request $request)
    {
        // Validar token de API
        $apiToken = $request->header('X-API-Token');
        if ($apiToken !== config('app.n8n_api_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $tomorrow = Carbon::tomorrow();
        $dayOfWeek = $tomorrow->dayOfWeek; // 0=Domingo, 1=Lunes, etc.

        // Obtener todas las asignaciones del día siguiente
        $assignments = Assignment::whereHas('schedule', function ($query) use ($dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek);
        })
        ->with([
            'teacher.user',
            'classroom',
            'subject',
            'studentGroup',
            'schedule'
        ])
        ->get();

        // Agrupar por profesor
        $teacherClasses = [];

        foreach ($assignments as $assignment) {
            if (!$assignment->teacher || !$assignment->teacher->user) {
                continue;
            }

            $teacherId = $assignment->teacher->id;
            
            if (!isset($teacherClasses[$teacherId])) {
                $teacherClasses[$teacherId] = [
                    'teacher_id' => $teacherId,
                    'teacher_name' => $assignment->teacher->full_name,
                    'email' => $assignment->teacher->user->email,
                    'date' => $tomorrow->format('Y-m-d'),
                    'date_formatted' => $tomorrow->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                    'classes' => []
                ];
            }

            $teacherClasses[$teacherId]['classes'][] = [
                'subject' => $assignment->subject->name ?? 'Sin asignatura',
                'group' => $assignment->studentGroup->name ?? 'Sin grupo',
                'classroom' => $assignment->classroom->name ?? 'Sin salón',
                'classroom_location' => $assignment->classroom->location ?? 'Ubicación no especificada',
                'classroom_building' => $assignment->classroom->building ?? null,
                'start_time' => $assignment->schedule->start_time ?? '00:00',
                'end_time' => $assignment->schedule->end_time ?? '00:00',
                'duration_hours' => $assignment->schedule ? 
                    Carbon::parse($assignment->schedule->end_time)->diffInHours(
                        Carbon::parse($assignment->schedule->start_time)
                    ) : 0,
            ];
        }

        // Ordenar clases por hora de inicio
        foreach ($teacherClasses as &$teacher) {
            usort($teacher['classes'], function ($a, $b) {
                return strcmp($a['start_time'], $b['start_time']);
            });
        }

        return response()->json([
            'success' => true,
            'date' => $tomorrow->format('Y-m-d'),
            'day_name' => $tomorrow->locale('es')->dayName,
            'total_teachers' => count($teacherClasses),
            'total_classes' => $assignments->count(),
            'teachers' => array_values($teacherClasses)
        ]);
    }

    /**
     * Obtener estadísticas del sistema para informe diario al admin
     */
    public function getDailyStats(Request $request)
    {
        // Validar token de API
        $apiToken = $request->header('X-API-Token');
        if ($apiToken !== config('app.n8n_api_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Estadísticas generales
        $stats = [
            'date' => $today->format('Y-m-d'),
            'date_formatted' => $today->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
            
            // Clases de mañana
            'tomorrow_classes' => Assignment::whereHas('schedule', function ($query) use ($tomorrow) {
                $query->where('day_of_week', $tomorrow->dayOfWeek);
            })->count(),
            
            // Profesores activos
            'active_teachers' => Teacher::where('estado', 'activo')->count(),
            
            // Profesores invitados por expirar (próximos 7 días)
            'guest_teachers_expiring_soon' => Teacher::where('is_guest', true)
                ->whereBetween('access_expires_at', [$today, $today->copy()->addDays(7)])
                ->count(),
            
            // Profesores invitados expirados
            'guest_teachers_expired' => Teacher::where('is_guest', true)
                ->where('access_expires_at', '<', $today)
                ->count(),
            
            // Salones disponibles
            'total_classrooms' => \App\Modules\Infraestructura\Models\Classroom::where('estado', 'activo')->count(),
            
            // Asignaciones sin conflictos
            'assignments_with_conflicts' => 0, // TODO: implementar detección
            
            // Reservas pendientes
            'pending_reservations' => \App\Modules\Infraestructura\Models\Reservation::where('status', 'pendiente')->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Obtener conflictos detectados para alertas
     */
    public function getConflicts(Request $request)
    {
        // Validar token de API
        $apiToken = $request->header('X-API-Token');
        if ($apiToken !== config('app.n8n_api_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // TODO: Implementar detección completa de conflictos
        $conflicts = [];

        return response()->json([
            'success' => true,
            'total_conflicts' => count($conflicts),
            'conflicts' => $conflicts
        ]);
    }
}
