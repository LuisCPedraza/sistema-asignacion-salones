<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    /**
     * Mostrar los cursos asignados al profesor autenticado
     */
    public function misCursos()
    {
        // Obtener el profesor basado en el usuario autenticado
        $teacher = Teacher::where('user_id', auth()->id())->first();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        // Obtener todas las asignaciones del profesor con sus relaciones
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with([
                'subject',
                'group.semester',
                'group.career',
                'classroom.building'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Agrupar por materia para evitar duplicados
        $cursos = $assignments->groupBy('subject_id')->map(function ($assignmentGroup) {
            $firstAssignment = $assignmentGroup->first();
            
            return [
                'subject' => $firstAssignment->subject,
                'groups' => $assignmentGroup->map(function ($assignment) {
                    return [
                        'id' => $assignment->id,
                        'group' => $assignment->group,
                        'classroom' => $assignment->classroom,
                        'day' => $assignment->day,
                        'start_time' => $assignment->start_time,
                        'end_time' => $assignment->end_time,
                        'student_count' => $assignment->group->student_count ?? 0,
                    ];
                }),
                'total_students' => $assignmentGroup->sum(fn($a) => $a->group->student_count ?? 0),
                'total_groups' => $assignmentGroup->count(),
            ];
        })->values();

        return view('profesor.mis-cursos', [
            'cursos' => $cursos,
            'teacher' => $teacher,
            'totalAssignments' => $assignments->count(),
        ]);
    }

    /**
     * Mostrar detalles de un curso específico
     */
    public function detalleCurso($assignmentId)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->with([
                'subject',
                'group.semester',
                'group.career',
                'classroom.building',
                'teacher.user'
            ])
            ->firstOrFail();

        return view('profesor.detalle-curso', [
            'assignment' => $assignment,
            'teacher' => $teacher,
        ]);
    }
}
