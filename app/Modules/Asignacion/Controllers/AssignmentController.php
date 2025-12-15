<?php

namespace App\Modules\Asignacion\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\WebhookController;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;

// Modelos correctos del sistema modular
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Modules\Asignacion\Models\AssignmentRule;
use App\Modules\Asignacion\Models\Assignment;
use App\Models\AcademicPeriod;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    public function index()
    {
        return view('asignacion.index');
    }

    public function automatica()
    {
        $datos = [
            'gruposCount' => StudentGroup::where('is_active', true)->count(),
            'salonesCount' => Classroom::where('is_active', true)->count(),
            'profesoresCount' => Teacher::where('is_active', true)->count(),
            'franjasCount' => TimeSlot::count(),
            'reglasActivas' => AssignmentRule::where('is_active', true)->orderBy('weight', 'desc')->get(),
            'asignacionesExistentes' => Assignment::count(),
        ];

        return view('asignacion.automatica', $datos);
    }

    public function ejecutarAutomatica(Request $request)
    {
        // Allow more time for bulk assignment without hitting the default 30s limit in web context.
        set_time_limit(120);

        $inicio = microtime(true);

        try {
            DB::beginTransaction();

            $algorithm = new AssignmentAlgorithm();
            $asignaciones = $algorithm->generateAssignments();

            DB::commit();

            $duracion = round(microtime(true) - $inicio, 2);

            return redirect()
                ->route('asignacion.resultados')
                ->with('success_message', "¡Asignación completada con éxito! Se asignaron " . count($asignaciones) . " grupos en {$duracion} segundos");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en asignación automática', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('asignacion.automatica')
                ->with('error_message', 'Ocurrió un error durante la asignación: ' . $e->getMessage());
        }
    }

    public function resultados()
    {
        $asignaciones = Assignment::with(['group', 'teacher', 'classroom'])
            ->orderBy('score', 'desc')
            ->get();

        $algorithm = new AssignmentAlgorithm();

        // Estadísticas
        $totalAsignaciones = $asignaciones->count();
        $scorePromedio = $asignaciones->avg('score') ?? 0;
        $asignacionesExcelentes = $asignaciones->filter(fn($a) => $a->score >= 0.8)->count();
        $asignacionesRegulares = $asignaciones->filter(fn($a) => $a->score < 0.4)->count();

        return view('asignacion.resultados', compact(
            'asignaciones',
            'algorithm',
            'totalAsignaciones',
            'scorePromedio',
            'asignacionesExcelentes',
            'asignacionesRegulares'
        ));
    }

    public function manual(Request $request)
    {
        // Obtener período académico seleccionado o el activo
        $periodId = $request->get('period_id');
        $period = $periodId 
            ? AcademicPeriod::find($periodId)
            : AcademicPeriod::where('is_active', true)
                ->orderBy('start_date')
                ->first();
        
        // Obtener todos los períodos para el selector
        $periods = AcademicPeriod::orderBy('start_date', 'desc')->get();
        
        // Obtener carreras activas para filtro jerárquico
        $careers = \App\Models\Career::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Obtener filtros seleccionados
        $selectedCareer = $request->get('career_id');
        $selectedSemester = $request->get('semester_id');
        
        // Obtener semestres (filtrados por carrera si está seleccionada)
        $semestersQuery = \App\Models\Semester::where('is_active', true);
        if ($selectedCareer) {
            $semestersQuery->where('career_id', $selectedCareer);
        }
        $semesters = $semestersQuery->orderBy('number')->get();
        
        // Log para debugging
        \Log::info('Filtros recibidos', [
            'selectedCareer' => $selectedCareer,
            'selectedSemester' => $selectedSemester,
            'total_semesters' => $semesters->count(),
            'semesters_sample' => $semesters->take(3)->map(fn($s) => [
                'id' => $s->id,
                'number' => $s->number,
                'career_id' => $s->career_id
            ])
        ]);
        
        // Obtener todos los datos necesarios para la vista (filtrados por semestre si está seleccionado)
        $groupsQuery = StudentGroup::with('semester.career')->where('is_active', true);
        if ($selectedSemester) {
            $groupsQuery->where('semester_id', $selectedSemester);
        }
        $groups = $groupsQuery->get();
        
        $classrooms = Classroom::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $subjects = \App\Models\Subject::where('is_active', true)->get();
        $timeSlots = TimeSlot::all();
        
        // Obtener asignaciones existentes con prioridad de filtros: semestre > carrera > período
        $query = Assignment::with(['group.semester.career', 'classroom', 'teacher', 'subject']);

        if ($selectedSemester) {
            // Priorizar semestre seleccionado
            $query->whereIn('student_group_id',
                StudentGroup::where('semester_id', $selectedSemester)->pluck('id')
            );
        } elseif ($selectedCareer) {
            // Si hay carrera seleccionada, filtrar por sus semestres
            $careerSemesterIds = \App\Models\Semester::where('career_id', $selectedCareer)->pluck('id');
            $query->whereIn('student_group_id',
                StudentGroup::whereIn('semester_id', $careerSemesterIds)->pluck('id')
            );
        } elseif ($period) {
            // Si no hay filtros de carrera/semestre, usar período (si existe)
            $query->whereIn('student_group_id', 
                StudentGroup::where('academic_period_id', $period->id)->pluck('id')
            );
        }
        
        $rawAssignments = $query->get();
        
        \Log::info('Total de asignaciones cargadas: ' . $rawAssignments->count());
        
        $assignments = $rawAssignments
            ->map(function ($assignment) {
                // Mapear día a fecha (usando semana iniciando en domingo para alinear con el calendario visible)
                $dayMap = [
                    'sunday' => 0,
                    'monday' => 1,
                    'tuesday' => 2,
                    'wednesday' => 3,
                    'thursday' => 4,
                    'friday' => 5,
                    'saturday' => 6,
                ];
                
                $daysFromSunday = $dayMap[strtolower($assignment->day)] ?? 0;
                $baseDate = now()->startOfWeek(Carbon::SUNDAY)->addDays($daysFromSunday)->format('Y-m-d');

                // Asegurar que las horas no traigan una fecha previa (evita duplicar fecha)
                $startTime = Carbon::parse($assignment->start_time)->format('H:i:s');
                $endTime = Carbon::parse($assignment->end_time)->format('H:i:s');

                return [
                    'id' => (string)$assignment->id,
                    'title' => $assignment->subject?->name ?? 'Sin materia',
                    'start' => $baseDate . 'T' . $startTime,
                    'end' => $baseDate . 'T' . $endTime,
                    'resourceId' => $assignment->classroom_id,
                    'backgroundColor' => $this->getColorByScore($assignment->score),
                    'borderColor' => $this->getColorByScore($assignment->score),
                    'extendedProps' => [
                        'group' => $assignment->group?->name ?? 'Sin grupo',
                        'group_id' => (int)$assignment->student_group_id,
                        'teacher' => (($assignment->teacher?->first_name ?? '') . ' ' . ($assignment->teacher?->last_name ?? '')),
                        'teacher_id' => (int)$assignment->teacher_id,
                        'classroom' => $assignment->classroom?->name ?? 'Sin salón',
                        'classroom_id' => (int)$assignment->classroom_id,
                        'subject' => $assignment->subject?->name ?? 'Sin materia',
                        'subject_id' => (int)$assignment->subject_id,
                        'day' => strtolower($assignment->day),
                        'score' => round($assignment->score * 100, 1) . '%',
                    ],
                ];
            });
        
        \Log::info('Eventos mapeados: ' . $assignments->count());

        return view('asignacion.manual', compact(
            'groups',
            'classrooms',
            'teachers',
            'subjects',
            'timeSlots',
            'assignments',
            'periods',
            'period',
            'careers',
            'semesters',
            'selectedCareer',
            'selectedSemester'
        ));
    }

    public function exportManualPdf(Request $request)
    {
        $periodId = $request->get('period_id');
        $selectedCareer = $request->get('career_id');
        $selectedSemester = $request->get('semester_id');

        // Reutilizar lógica de filtros
        $period = $periodId 
            ? AcademicPeriod::find($periodId)
            : AcademicPeriod::where('is_active', true)
                ->orderBy('start_date')
                ->first();

        $query = Assignment::with(['group.semester.career', 'classroom', 'teacher', 'subject']);

        if ($selectedSemester) {
            $query->whereIn('student_group_id',
                StudentGroup::where('semester_id', $selectedSemester)->pluck('id')
            );
        } elseif ($selectedCareer) {
            $careerSemesterIds = \App\Models\Semester::where('career_id', $selectedCareer)->pluck('id');
            $query->whereIn('student_group_id',
                StudentGroup::whereIn('semester_id', $careerSemesterIds)->pluck('id')
            );
        } elseif ($period) {
            $query->whereIn('student_group_id', 
                StudentGroup::where('academic_period_id', $period->id)->pluck('id')
            );
        }

        $assignments = $query->orderBy('day')->orderBy('start_time')->get();

        $pdf = Pdf::loadView('asignacion.manual-pdf', [
            'assignments' => $assignments,
            'period' => $period,
            'selectedCareer' => $selectedCareer,
            'selectedSemester' => $selectedSemester,
            'generated_at' => now()->format('d/m/Y H:i')
        ])->setPaper('a4', 'portrait');

        $filename = 'asignaciones_manual_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($filename);
    }

    private function getColorByScore($score)
    {
        if ($score >= 0.8) return '#28a745'; // Verde
        if ($score >= 0.6) return '#ffc107'; // Amarillo
        if ($score >= 0.4) return '#fd7e14'; // Naranja
        return '#dc3545'; // Rojo
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'student_group_id' => 'required|exists:student_groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $assignment = Assignment::create([
            ...$validated,
            'score' => 0.7, // Score por defecto para asignaciones manuales
            'assigned_by_algorithm' => false,
            'is_confirmed' => true,
            'notes' => 'Asignación manual por ' . auth()->user()->name,
        ]);

        // 🔔 Disparar webhook a n8n
        WebhookController::notifyAssignmentCreated($assignment);

        return response()->json([
            'success' => true,
            'message' => 'Asignación creada exitosamente',
            'assignment' => $assignment,
        ]);
    }

    public function updateManual(Request $request, Assignment $assignment)
    {
        // Guardar estado anterior para comparar
        $oldAssignment = $assignment->replicate();
        $oldAssignment->load(['teacher', 'classroom', 'group', 'subject']);

        $validated = $request->validate([
            'day' => 'sometimes|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
            'classroom_id' => 'sometimes|exists:classrooms,id',
        ]);

        $assignment->update([
            ...$validated,
            'notes' => 'Actualizado manualmente por ' . auth()->user()->name . ' - ' . now()->format('Y-m-d H:i'),
        ]);

        // Recargar relaciones para el nuevo estado
        $assignment->load(['teacher', 'classroom', 'group', 'subject']);

        // 🔔 Disparar webhook a n8n
        WebhookController::notifyAssignmentUpdated($oldAssignment, $assignment);

        return response()->json([
            'success' => true,
            'message' => 'Asignación actualizada exitosamente',
            'assignment' => $assignment,
        ]);
    }

    public function destroyManual(Assignment $assignment)
    {
        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asignación eliminada exitosamente',
        ]);
    }

    public function conflictos()
    {
        $detector = new \App\Modules\Asignacion\Services\ConflictDetector();
        $conflictReport = $detector->getConflictReport();
        $allConflicts = $detector->detectAllConflicts();

        return view('asignacion.conflictos', [
            'conflictReport' => $conflictReport,
            'allConflicts' => $allConflicts,
        ]);
    }
}