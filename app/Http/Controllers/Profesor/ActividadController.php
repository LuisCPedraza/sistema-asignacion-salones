<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityGrade;
use App\Models\Student;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ActividadController extends Controller
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

    public function index()
    {
        $teacher = $this->currentTeacher();
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with([
                'subject',
                'group.students',
                'classroom',
                'activities.grades',
            ])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('profesor.actividades.index', [
            'assignments' => $assignments,
            'teacher' => $teacher,
        ]);
    }

    public function create(Request $request)
    {
        $teacher = $this->currentTeacher();
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignmentId = $request->query('assignment_id');

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'classroom'])
            ->firstOrFail();

        return view('profesor.actividades.create', [
            'assignment' => $assignment,
        ]);
    }

    public function store(Request $request)
    {
        $teacher = $this->currentTeacher();
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'max_score' => 'required|numeric|min:1|max:999',
        ]);

        $assignment = Assignment::where('id', $validated['assignment_id'])
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        Activity::create([
            'assignment_id' => $assignment->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'max_score' => $validated['max_score'],
            'due_date' => $validated['due_date'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('profesor.actividades.index')
            ->with('success', 'Actividad creada correctamente.');
    }

    public function calificar($activityId)
    {
        $teacher = $this->currentTeacher();
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $activity = Activity::with([
                'assignment.subject.career',
                'assignment.group.semester.career',
                'assignment.group.students' => function ($q) {
                    $q->orderBy('apellido')->orderBy('nombre');
                },
                'assignment.classroom.building'
            ])
            ->whereHas('assignment', fn ($q) => $q->where('teacher_id', $teacher->id))
            ->findOrFail($activityId);

        $grades = ActivityGrade::where('activity_id', $activityId)
            ->get()
            ->keyBy('student_id');

        return view('profesor.actividades.calificar', [
            'activity' => $activity,
            'grades' => $grades,
            'maxScore' => $activity->max_score,
            'teacher' => $teacher,
        ]);
    }

    public function guardarCalificaciones(Request $request, $activityId)
    {
        $teacher = $this->currentTeacher();
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $activity = Activity::with(['assignment.group'])
            ->whereHas('assignment', fn ($q) => $q->where('teacher_id', $teacher->id))
            ->findOrFail($activityId);

        $maxScore = $activity->max_score ?? 100;

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:' . $maxScore,
            'feedback' => 'array',
            'feedback.*' => 'nullable|string',
        ]);

        $studentIds = array_keys($validated['grades']);

        $validStudents = Student::whereIn('id', $studentIds)
            ->where('group_id', $activity->assignment->student_group_id)
            ->pluck('id')
            ->toArray();

        if (count($validStudents) !== count($studentIds)) {
            return Redirect::back()->with('error', 'Hay estudiantes no válidos para este grupo.');
        }

        DB::transaction(function () use ($validated, $activityId) {
            foreach ($validated['grades'] as $studentId => $score) {
                ActivityGrade::updateOrCreate(
                    [
                        'activity_id' => $activityId,
                        'student_id' => $studentId,
                    ],
                    [
                        'score' => $score !== '' ? $score : null,
                        'feedback' => $validated['feedback'][$studentId] ?? null,
                        'graded_at' => now(),
                        'graded_by' => auth()->id(),
                    ]
                );
            }
        });

        return redirect()->route('profesor.actividades.index')
            ->with('success', 'Calificaciones guardadas correctamente.');
    }
}
