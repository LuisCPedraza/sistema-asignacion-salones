<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    private function currentTeacher()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        return $user->teacher_id
            ? Teacher::find($user->teacher_id)
            : Teacher::where('user_id', $user->id)->first();
    }

    /**
     * Mostrar lista de cursos para tomar asistencia
     */
    public function index()
    {
        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        // Obtener asignaciones agrupadas por materia
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with([
                'subject.career',
                'group.semester.career',
                'group.career',
                'classroom.building'
            ])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $cursos = $assignments->groupBy('subject_id')->map(function ($assignmentGroup) {
            $firstAssignment = $assignmentGroup->first();
            $career = $firstAssignment->subject->career
                ?? optional($firstAssignment->group->semester)->career
                ?? $firstAssignment->group->career;

            return [
                'subject' => $firstAssignment->subject,
                'career' => $career,
                'groups' => $assignmentGroup->map(function ($assignment) {
                    return [
                        'assignment_id' => $assignment->id,
                        'group' => $assignment->group,
                        'classroom' => $assignment->classroom,
                        'building' => optional($assignment->classroom)->building,
                        'semester' => optional($assignment->group)->semester,
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
        $teacher = $this->currentTeacher();
        
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

        $fecha = request('fecha', now()->format('Y-m-d'));

        $asistenciasPrevias = Attendance::where('assignment_id', $assignmentId)
            ->whereDate('fecha', $fecha)
            ->pluck('status', 'student_id');

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
            'fecha' => $fecha,
            'asistenciasPrevias' => $asistenciasPrevias,
        ]);
    }

    /**
     * Guardar registro de asistencia
     */
    public function guardarAsistencia(Request $request, $assignmentId)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|before_or_equal:today',
            'asistencias' => 'required|array|min:1',
            'asistencias.*' => 'required|in:presente,ausente,tardanza,justificado',
        ]);

        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $studentIds = array_keys($validated['asistencias']);

        // Verificar que los estudiantes pertenezcan al grupo del assignment
        $validStudentIds = Student::whereIn('id', $studentIds)
            ->where('group_id', $assignment->student_group_id)
            ->pluck('id')
            ->toArray();

        if (count($validStudentIds) !== count($studentIds)) {
            return redirect()->route('profesor.asistencias.tomar', $assignmentId)
                ->with('error', 'Hay estudiantes no válidos para este grupo.');
        }

        $fechaNormalizada = Carbon::parse($validated['fecha'])->startOfDay()->toDateTimeString();

        DB::transaction(function () use ($validated, $assignmentId, $fechaNormalizada) {
            foreach ($validated['asistencias'] as $studentId => $status) {
                Attendance::updateOrCreate(
                    [
                        'assignment_id' => $assignmentId,
                        'student_id' => $studentId,
                        'fecha' => $fechaNormalizada,
                    ],
                    [
                        'status' => $status,
                        'taken_by' => auth()->id(),
                    ]
                );
            }
        });

        $totalEstudiantes = count($validated['asistencias']);
        $presentes = collect($validated['asistencias'])->filter(fn($a) => $a === 'presente')->count();
        $ausentes = collect($validated['asistencias'])->filter(fn($a) => $a === 'ausente')->count();
        $tardanzas = collect($validated['asistencias'])->filter(fn($a) => $a === 'tardanza')->count();

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

        $attendanceByDay = Attendance::where('assignment_id', $assignmentId)
            ->orderBy('fecha', 'desc')
            ->get()
            ->groupBy(fn ($a) => $a->fecha->format('Y-m-d'))
            ->sortKeysDesc();

        $totalGrupo = $assignment->group->students()->count();

        $historial = $attendanceByDay->map(function ($registros, $fecha) use ($totalGrupo) {
            $presentes = $registros->where('status', 'presente')->count();
            $ausentes = $registros->where('status', 'ausente')->count();
            $tardanzas = $registros->where('status', 'tardanza')->count();
            $justificados = $registros->where('status', 'justificado')->count();

            $total = $totalGrupo > 0 ? $totalGrupo : max($registros->count(), 1);
            $porcentaje = round((($presentes + $tardanzas + $justificados) / $total) * 100, 1);

            return [
                'fecha' => $fecha,
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'tardanzas' => $tardanzas,
                'justificados' => $justificados,
                'porcentaje_asistencia' => min($porcentaje, 100),
            ];
        })->take(10);

        $promedioAsistencia = $historial->avg('porcentaje_asistencia') ? round($historial->avg('porcentaje_asistencia'), 1) : 0;
        $promedioPresentes = $historial->avg('presentes') ? round($historial->avg('presentes'), 1) : 0;
        $promedioAusentes = $historial->avg('ausentes') ? round($historial->avg('ausentes'), 1) : 0;
        $promedioTardanzas = $historial->avg('tardanzas') ? round($historial->avg('tardanzas'), 1) : 0;

        $historial = $historial->values()->all();

        return view('profesor.asistencias.historial', [
            'assignment' => $assignment,
            'historial' => $historial,
            'teacher' => $teacher,
            'promedioAsistencia' => $promedioAsistencia,
            'promedioPresentes' => $promedioPresentes,
            'promedioAusentes' => $promedioAusentes,
            'promedioTardanzas' => $promedioTardanzas,
        ]);
    }
}
