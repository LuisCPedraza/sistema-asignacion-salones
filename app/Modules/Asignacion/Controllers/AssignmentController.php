<?php

namespace App\Modules\Asignacion\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Asignacion\Models\AssignmentRule; // Agrego import para rules

class AssignmentController extends Controller
{
    private $algorithm;

    public function __construct(AssignmentAlgorithm $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * HU9: Vista de asignación automática
     */
    public function automatica()
    {
        $groups = StudentGroup::active()->get();
        $classrooms = Classroom::active()->get();
        $rules = AssignmentRule::active()->get(); // Fix: Directo del model

        return view('asignacion.automatica', compact('groups', 'classrooms', 'rules'));
    }

    /**
     * HU9: Ejecutar asignación automática (POST)
     */
    public function ejecutarAutomatica(Request $request)
    {
        $request->validate([
            'academic_period_id' => 'nullable|exists:academic_periods,id'
        ]);

        try {
            $generatedAssignments = $this->algorithm->generateAssignments($request->academic_period_id);
            
            $createdCount = 0;
            foreach ($generatedAssignments as $assignmentData) {
                if (!$this->hasConflict($assignmentData)) {
                    Assignment::create($assignmentData);
                    $createdCount++;
                }
            }

            return redirect()->route('asignacion.automatica')
                ->with('success', "Se generaron {$createdCount} asignaciones automáticamente.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al generar asignaciones: ' . $e->getMessage());
        }
    }

    /**
     * HU11: Vista de asignación manual
     */
    public function manual()
    {
        $groups = StudentGroup::active()->get();
        $teachers = Teacher::active()->get();
        $classrooms = Classroom::active()->get();
        $assignments = Assignment::with(['group', 'teacher', 'classroom'])->get();

        return view('asignacion.manual', compact('groups', 'teachers', 'classrooms', 'assignments'));
    }

    /**
     * HU11: Guardar asignación manual (POST)
     */
    public function guardarManual(Request $request)
    {
        $request->validate([
            'student_group_id' => 'required|exists:student_groups,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($this->hasConflict($request->all())) {
            return redirect()->back()
                ->with('error', 'Conflicto detectado.')
                ->withInput();
        }

        Assignment::create($request->all());

        return redirect()->route('asignacion.manual')
            ->with('success', 'Asignación creada exitosamente.');
    }

    // Resto métodos existentes (index, create, store, destroy, conflicts, hasConflict, findConflictsForAssignment) sin cambios...
    public function index()
    {
        $assignments = Assignment::with(['group', 'teacher', 'classroom'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('asignacion.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $groups = StudentGroup::active()->get();
        $teachers = Teacher::active()->get();
        $classrooms = Classroom::active()->get();

        return view('asignacion.assignments.create', compact('groups', 'teachers', 'classrooms'));
    }

    public function store(Request $request)
    {
        return $this->guardarManual($request);
    }

    public function conflictos()
    {
        $conflicts = [];
        $assignments = Assignment::with(['group', 'teacher', 'classroom'])->get();

        foreach ($assignments as $assignment) {
            $conflicting = $this->findConflictsForAssignment($assignment);
            if (!empty($conflicting)) {
                $conflicts[] = [
                    'assignment' => $assignment,
                    'conflicts' => $conflicting
                ];
            }
        }

        return view('asignacion.conflictos', compact('conflicts'));
    }

    public function generateAutomatically(Request $request)
    {
        return $this->ejecutarAutomatica($request);
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('asignacion.assignments.index')
            ->with('success', 'Asignación eliminada.');
    }

    private function hasConflict($assignmentData): bool
    {
        return Assignment::where(function($query) use ($assignmentData) {
                $query->where('classroom_id', $assignmentData['classroom_id'])
                      ->where('day', $assignmentData['day'])
                      ->where(function($q) use ($assignmentData) {
                          $q->whereBetween('start_time', [$assignmentData['start_time'], $assignmentData['end_time']])
                            ->orWhereBetween('end_time', [$assignmentData['start_time'], $assignmentData['end_time']])
                            ->orWhere(function($q2) use ($assignmentData) {
                                $q2->where('start_time', '<=', $assignmentData['start_time'])
                                   ->where('end_time', '>=', $assignmentData['end_time']);
                            });
                      });
            })
            ->orWhere(function($query) use ($assignmentData) {
                $query->where('teacher_id', $assignmentData['teacher_id'])
                      ->where('day', $assignmentData['day'])
                      ->where(function($q) use ($assignmentData) {
                          $q->whereBetween('start_time', [$assignmentData['start_time'], $assignmentData['end_time']])
                            ->orWhereBetween('end_time', [$assignmentData['start_time'], $assignmentData['end_time']]);
                      });
            })
            ->exists();
    }

    private function findConflictsForAssignment(Assignment $assignment): array
    {
        return Assignment::where('id', '!=', $assignment->id)
            ->where(function($query) use ($assignment) {
                $query->where('classroom_id', $assignment->classroom_id)
                      ->where('day', $assignment->day)
                      ->where(function($q) use ($assignment) {
                          $q->whereBetween('start_time', [$assignment->start_time, $assignment->end_time])
                            ->orWhereBetween('end_time', [$assignment->start_time, $assignment->end_time]);
                      });
            })
            ->orWhere(function($query) use ($assignment) {
                $query->where('teacher_id', $assignment->teacher_id)
                      ->where('day', $assignment->day)
                      ->where(function($q) use ($assignment) {
                          $q->whereBetween('start_time', [$assignment->start_time, $assignment->end_time])
                            ->orWhereBetween('end_time', [$assignment->start_time, $assignment->end_time]);
                      });
            })
            ->with(['group', 'teacher', 'classroom'])
            ->get()
            ->toArray();
    }
}