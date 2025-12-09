<?php

namespace App\Modules\Asignacion\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Asignacion\Models\Assignment;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeacherScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar horario personal del profesor
     */
    public function mySchedule()
    {
        $user = Auth::user();
        
        // Verificar que el usuario sea profesor
        if (!$user->hasRole('Profesor') && !$user->hasRole('profesor')) {
            abort(403, 'Acceso denegado. Solo profesores pueden ver su horario.');
        }
        
        // Obtener el profesor asociado al usuario
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return view('asignacion.schedule', [
                'assignments' => collect(),
                'teacher' => null,
                'totalHours' => 0,
                'groupsCount' => 0,
                'message' => 'No hay un profesor asociado a tu cuenta.'
            ]);
        }
        
        // Obtener asignaciones del profesor
        $rawAssignments = Assignment::with(['group', 'classroom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
        
        // Mapear asignaciones a eventos de FullCalendar
        $assignments = $rawAssignments
            ->map(function ($assignment) {
                $dayMap = [
                    'monday' => 0,
                    'tuesday' => 1,
                    'wednesday' => 2,
                    'thursday' => 3,
                    'friday' => 4,
                    'saturday' => 5,
                    'sunday' => 6,
                ];
                
                $daysFromMonday = $dayMap[strtolower($assignment->day)] ?? 0;
                $baseDate = now()->startOfWeek()->addDays($daysFromMonday)->format('Y-m-d');
                
                $startTime = Carbon::parse($assignment->start_time)->format('H:i:s');
                $endTime = Carbon::parse($assignment->end_time)->format('H:i:s');
                
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->subject->name ?? 'Sin materia',
                    'start' => $baseDate . 'T' . $startTime,
                    'end' => $baseDate . 'T' . $endTime,
                    'backgroundColor' => $this->getColorByScore($assignment->score),
                    'borderColor' => $this->getColorByScore($assignment->score),
                    'extendedProps' => [
                        'group' => $assignment->group->name ?? 'Sin grupo',
                        'classroom' => $assignment->classroom->name ?? 'Sin salón',
                        'subject' => $assignment->subject->name ?? 'Sin materia',
                        'day' => strtolower($assignment->day),
                        'score' => round($assignment->score * 100, 1) . '%',
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                    ],
                ];
            });
        
        // Calcular estadísticas
        $totalHours = 0;
        $groupsSet = collect();
        
        foreach ($rawAssignments as $assignment) {
            $start = Carbon::parse($assignment->start_time);
            $end = Carbon::parse($assignment->end_time);
            $diff = $end->diffInMinutes($start) / 60;
            $totalHours += $diff;
            
            $groupsSet->push($assignment->group->name);
        }
        
        $uniqueGroups = $groupsSet->unique()->count();
        
        return view('asignacion.schedule', [
            'assignments' => $assignments,
            'rawAssignments' => $rawAssignments,
            'teacher' => $teacher,
            'user' => $user,
            'totalHours' => round($totalHours, 1),
            'groupsCount' => $uniqueGroups,
            'assignmentsCount' => $rawAssignments->count(),
        ]);
    }
    
    /**
     * Descargar horario en PDF
     */
    public function downloadSchedule()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Profesor') && !$user->hasRole('profesor')) {
            abort(403, 'Acceso denegado.');
        }
        
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            abort(404, 'Profesor no encontrado.');
        }
        
        $assignments = Assignment::with(['group', 'classroom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
        
        // Agrupar por día
        $byDay = $assignments->groupBy('day');
        
        // Generar PDF simple (usando Blade)
        $html = view('asignacion.schedule-pdf', [
            'teacher' => $teacher,
            'assignments' => $byDay,
        ])->render();
        
        return response()->streamDownload(
            function () use ($html) {
                echo $html;
            },
            "horario_" . str_replace(' ', '_', $teacher->first_name . '_' . $teacher->last_name) . ".html"
        );
    }
    
    /**
     * Obtener color basado en score
     */
    private function getColorByScore($score)
    {
        if ($score >= 0.8) return '#28a745'; // Verde
        if ($score >= 0.6) return '#ffc107'; // Amarillo
        if ($score >= 0.4) return '#fd7e14'; // Naranja
        return '#dc3545'; // Rojo
    }
}
