<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Activity;
use App\Models\ActivityGrade;
use App\Models\Student;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
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
     * Mostrar página de reportes académicos
     */
    public function index()
    {
        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with([
                'subject.career',
                'group.semester.career',
                'group.students',
                'classroom.building'
            ])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('profesor.reportes.index', compact('assignments', 'teacher'));
    }

    /**
     * Generar reporte de asistencias por curso
     */
    public function asistencias($assignmentId)
    {
        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'group.students'])
            ->firstOrFail();

        $estudiantes = $assignment->group->students;
        
        $asistenciasPorEstudiante = $estudiantes->map(function ($estudiante) use ($assignmentId) {
            $total = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->count();

            $presentes = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->where('status', 'presente')
                ->count();

            $ausentes = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->where('status', 'ausente')
                ->count();

            $tardanzas = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->where('status', 'tardanza')
                ->count();

            $porcentaje = $total > 0 ? round(($presentes / $total) * 100, 2) : 0;

            return [
                'estudiante' => $estudiante,
                'total' => $total,
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'tardanzas' => $tardanzas,
                'porcentaje' => $porcentaje,
            ];
        });

        $estadisticas = [
            'totalClases' => Attendance::where('assignment_id', $assignmentId)
                ->distinct('fecha')
                ->count('fecha'),
            'promedioAsistencia' => $asistenciasPorEstudiante->avg('porcentaje'),
            'estudiantesAlerta' => $asistenciasPorEstudiante->where('porcentaje', '<', 75)->count(),
        ];

        return view('profesor.reportes.asistencias', compact('assignment', 'asistenciasPorEstudiante', 'estadisticas'));
    }

    /**
     * Generar reporte de actividades y calificaciones
     */
    public function actividades($assignmentId)
    {
        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'group.students'])
            ->firstOrFail();

        $activities = Activity::where('assignment_id', $assignmentId)
            ->with('grades')
            ->get();

        $calificacionesPorEstudiante = $assignment->group->students->map(function ($estudiante) use ($assignmentId) {
            $grades = ActivityGrade::whereHas('activity', function ($q) use ($assignmentId) {
                $q->where('assignment_id', $assignmentId);
            })
                ->where('student_id', $estudiante->id)
                ->get();

            $totalPosible = Activity::where('assignment_id', $assignmentId)->sum('max_score');
            $totalObtenido = $grades->sum('score');
            $promedio = $totalPosible > 0 ? round(($totalObtenido / $totalPosible) * 100, 2) : 0;

            return [
                'estudiante' => $estudiante,
                'totalObtenido' => $totalObtenido,
                'totalPosible' => $totalPosible,
                'promedio' => $promedio,
                'calificaciones' => $grades,
            ];
        });

        $estadisticas = [
            'totalActividades' => $activities->count(),
            'promedioGeneral' => $calificacionesPorEstudiante->avg('promedio'),
            'mejorCalificacion' => $calificacionesPorEstudiante->max('promedio'),
            'peorCalificacion' => $calificacionesPorEstudiante->min('promedio'),
        ];

        return view('profesor.reportes.actividades', compact('assignment', 'activities', 'calificacionesPorEstudiante', 'estadisticas'));
    }

    /**
     * Exportar reporte de asistencias a PDF
     */
    public function exportAsistenciasPdf($assignmentId)
    {
        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'group.students'])
            ->firstOrFail();

        $estudiantes = $assignment->group->students;
        
        $asistenciasPorEstudiante = $estudiantes->map(function ($estudiante) use ($assignmentId) {
            $total = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->count();

            $presentes = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->where('status', 'presente')
                ->count();

            $ausentes = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->where('status', 'ausente')
                ->count();

            $tardanzas = Attendance::where('assignment_id', $assignmentId)
                ->where('student_id', $estudiante->id)
                ->where('status', 'tardanza')
                ->count();

            $porcentaje = $total > 0 ? round(($presentes / $total) * 100, 2) : 0;

            return [
                'estudiante' => $estudiante,
                'total' => $total,
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'tardanzas' => $tardanzas,
                'porcentaje' => $porcentaje,
            ];
        });

        $estadisticas = [
            'totalClases' => Attendance::where('assignment_id', $assignmentId)
                ->distinct('fecha')
                ->count('fecha'),
            'promedioAsistencia' => $asistenciasPorEstudiante->avg('porcentaje'),
            'estudiantesAlerta' => $asistenciasPorEstudiante->where('porcentaje', '<', 75)->count(),
        ];

        $pdf = Pdf::loadView('profesor.reportes.asistencias-pdf', [
            'assignment' => $assignment,
            'asistenciasPorEstudiante' => $asistenciasPorEstudiante,
            'estadisticas' => $estadisticas,
            'fechaExporte' => Carbon::now()->format('d/m/Y H:i'),
        ]);

        $fileName = 'reporte-asistencias-' . ($assignment->subject->code ?? 'curso') . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Exportar reporte de actividades a PDF
     */
    public function exportActividadesPdf($assignmentId)
    {
        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'group.students'])
            ->firstOrFail();

        $activities = Activity::where('assignment_id', $assignmentId)
            ->with('grades')
            ->get();

        $calificacionesPorEstudiante = $assignment->group->students->map(function ($estudiante) use ($assignmentId) {
            $grades = ActivityGrade::whereHas('activity', function ($q) use ($assignmentId) {
                $q->where('assignment_id', $assignmentId);
            })
                ->where('student_id', $estudiante->id)
                ->get();

            $totalPosible = Activity::where('assignment_id', $assignmentId)->sum('max_score');
            $totalObtenido = $grades->sum('score');
            $promedio = $totalPosible > 0 ? round(($totalObtenido / $totalPosible) * 100, 2) : 0;

            return [
                'estudiante' => $estudiante,
                'totalObtenido' => $totalObtenido,
                'totalPosible' => $totalPosible,
                'promedio' => $promedio,
                'calificaciones' => $grades,
            ];
        });

        $estadisticas = [
            'totalActividades' => $activities->count(),
            'promedioGeneral' => $calificacionesPorEstudiante->avg('promedio'),
            'mejorCalificacion' => $calificacionesPorEstudiante->max('promedio'),
            'peorCalificacion' => $calificacionesPorEstudiante->min('promedio'),
        ];

        $pdf = Pdf::loadView('profesor.reportes.actividades-pdf', [
            'assignment' => $assignment,
            'activities' => $activities,
            'calificacionesPorEstudiante' => $calificacionesPorEstudiante,
            'estadisticas' => $estadisticas,
            'fechaExporte' => Carbon::now()->format('d/m/Y H:i'),
        ]);

        $fileName = 'reporte-actividades-' . ($assignment->subject->code ?? 'curso') . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}
