<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Mostrar lista de cursos para tomar asistencia
     */
    public function index()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        // Obtener asignaciones agrupadas por materia
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with([
                'subject',
                'group.semester',
                'group.career',
                'classroom'
            ])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $cursos = $assignments->groupBy('subject_id')->map(function ($assignmentGroup) {
            $firstAssignment = $assignmentGroup->first();
            
            return [
                'subject' => $firstAssignment->subject,
                'groups' => $assignmentGroup->map(function ($assignment) {
                    return [
                        'assignment_id' => $assignment->id,
                        'group' => $assignment->group,
                        'classroom' => $assignment->classroom,
                        'day' => $assignment->day,
                        'start_time' => $assignment->start_time,
                        'end_time' => $assignment->end_time,
                        'student_count' => $assignment->group->student_count ?? 0,
                    ];
                }),
                'total_students' => $assignmentGroup->sum(fn($a) => $a->group->student_count ?? 0),
            ];
        })->values();

        return view('profesor.asistencias.index', [
            'cursos' => $cursos,
            'teacher' => $teacher,
        ]);
    }

    /**
     * Mostrar formulario para tomar asistencia de un grupo específico
     */
    public function tomarAsistencia($assignmentId)
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
                'group.students' => function($query) {
                    $query->activos()->orderBy('apellido')->orderBy('nombre');
                },
                'classroom.building'
            ])
            ->firstOrFail();

        // Obtener estudiantes reales del grupo
        $estudiantes = $assignment->group->students->map(function($student) {
            return [
                'id' => $student->id,
                'codigo' => $student->codigo,
                'nombre' => $student->nombre_completo,
            ];
        })->toArray();

        return view('profesor.asistencias.tomar', [
            'assignment' => $assignment,
            'estudiantes' => $estudiantes,
            'teacher' => $teacher,
            'fecha' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Guardar registro de asistencia
     */
    public function guardarAsistencia(Request $request, $assignmentId)
    {
        $request->validate([
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*' => 'in:presente,ausente,tardanza,justificado',
        ]);

        $teacher = Teacher::where('user_id', auth()->id())->first();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        // Aquí guardarías en la base de datos real
        // Por ahora, solo simulamos el guardado
        $totalEstudiantes = count($request->asistencias);
        $presentes = collect($request->asistencias)->filter(fn($a) => $a === 'presente')->count();
        $ausentes = collect($request->asistencias)->filter(fn($a) => $a === 'ausente')->count();
        $tardanzas = collect($request->asistencias)->filter(fn($a) => $a === 'tardanza')->count();

        return redirect()->route('profesor.asistencias.index')
            ->with('success', "Asistencia registrada exitosamente. Presentes: {$presentes}, Ausentes: {$ausentes}, Tardanzas: {$tardanzas}");
    }

    /**
     * Ver historial de asistencias de un grupo
     */
    public function historial($assignmentId)
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
                'classroom'
            ])
            ->firstOrFail();

        // Generar datos de ejemplo para el historial
        $historial = $this->generarHistorialEjemplo($assignment);

        return view('profesor.asistencias.historial', [
            'assignment' => $assignment,
            'historial' => $historial,
            'teacher' => $teacher,
        ]);
    }

    /**
     * Generar lista de estudiantes simulada
     */
    private function generarListaEstudiantes($cantidad)
    {
        $estudiantes = [];
        for ($i = 1; $i <= $cantidad; $i++) {
            $estudiantes[] = [
                'id' => $i,
                'nombre' => "Estudiante {$i}",
                'codigo' => sprintf("EST%04d", $i),
            ];
        }
        return $estudiantes;
    }

    /**
     * Generar historial de ejemplo
     */
    private function generarHistorialEjemplo($assignment)
    {
        $historial = [];
        $studentCount = $assignment->group->student_count ?? 20;
        
        // Generar registros de las últimas 2 semanas
        for ($i = 0; $i < 10; $i++) {
            $fecha = now()->subDays($i);
            $presentes = rand(15, $studentCount);
            $ausentes = $studentCount - $presentes;
            
            $historial[] = [
                'fecha' => $fecha->format('Y-m-d'),
                'fecha_texto' => $fecha->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'total_estudiantes' => $studentCount,
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'tardanzas' => rand(0, 3),
                'porcentaje_asistencia' => round(($presentes / $studentCount) * 100, 1),
            ];
        }

        return $historial;
    }
}
