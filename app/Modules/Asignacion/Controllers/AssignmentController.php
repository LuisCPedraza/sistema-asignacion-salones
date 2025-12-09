<?php

namespace App\Modules\Asignacion\Controllers;

use App\Http\Controllers\Controller;
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
        
        // Obtener todos los datos necesarios para la vista
        $groups = StudentGroup::with('semester.career')->where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $subjects = \App\Models\Subject::where('is_active', true)->get();
        $timeSlots = TimeSlot::all();
        
        // Obtener asignaciones existentes (filtradas por período si existe)
        $query = Assignment::with(['group', 'classroom', 'teacher', 'subject']);
        
        if ($period) {
            // Filtrar por período académico a través de la relación con StudentGroup
            $query->whereIn('student_group_id', 
                StudentGroup::where('academic_period_id', $period->id)->pluck('id')
            );
        }
        
        $rawAssignments = $query->get();
        
        \Log::info('Total de asignaciones cargadas: ' . $rawAssignments->count());
        
        $assignments = $rawAssignments
            ->map(function ($assignment) {
                // Mapear día a fecha (usando lunes como referencia)
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
            'period'
        ));
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

        return response()->json([
            'success' => true,
            'message' => 'Asignación creada exitosamente',
            'assignment' => $assignment,
        ]);
    }

    public function updateManual(Request $request, Assignment $assignment)
    {
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
        return view('asignacion.conflictos');
    }
}