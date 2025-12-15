<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Asignacion\Models\Assignment;
use Illuminate\Http\Request;

class EstudianteController extends Controller
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
     * Mostrar lista de estudiantes de los grupos del profesor
     */
    public function index()
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        // Obtener todos los assignments del profesor con sus grupos y estudiantes
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with([
                'subject.career',
                'group.semester.career',
                'group.students' => function($query) {
                    $query->orderBy('apellido')->orderBy('nombre');
                },
                'classroom'
            ])
            ->get();

        // Agrupar por materia
        $cursos = [];
        foreach ($assignments as $assignment) {
            $subjectId = $assignment->subject_id;
            
            if (!isset($cursos[$subjectId])) {
                $career = $assignment->subject->career
                    ?? optional($assignment->group->semester)->career
                    ?? optional($assignment->group)->career;

                $cursos[$subjectId] = [
                    'subject' => $assignment->subject,
                    'career' => $career,
                    'groups' => [],
                    'total_students' => 0,
                ];
            }

            $students = $assignment->group->students ?? collect();
            
            $cursos[$subjectId]['groups'][] = [
                'assignment_id' => $assignment->id,
                'group' => $assignment->group,
                'classroom' => $assignment->classroom,
                'semester' => optional($assignment->group)->semester,
                'students' => $students,
                'student_count' => $students->count(),
            ];

            $cursos[$subjectId]['total_students'] += $students->count();
        }

        return view('profesor.estudiantes.index', [
            'cursos' => $cursos,
            'totalEstudiantes' => collect($cursos)->sum('total_students'),
        ]);
    }

    /**
     * Mostrar formulario para crear un nuevo estudiante
     */
    public function create(Request $request)
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        // Obtener el assignment_id de la query string
        $assignmentId = $request->query('assignment_id');
        
        if (!$assignmentId) {
            return redirect()->route('profesor.estudiantes.index')
                ->with('error', 'Debe seleccionar un grupo.');
        }

        $assignment = Assignment::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'classroom'])
            ->first();

        if (!$assignment) {
            return redirect()->route('profesor.estudiantes.index')
                ->with('error', 'Grupo no encontrado o no autorizado.');
        }

        return view('profesor.estudiantes.create', [
            'assignment' => $assignment,
        ]);
    }

    /**
     * Guardar un nuevo estudiante
     */
    public function store(Request $request)
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        // Validar que el assignment pertenezca al profesor
        $assignment = Assignment::where('id', $request->assignment_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$assignment) {
            return redirect()->route('profesor.estudiantes.index')
                ->with('error', 'Grupo no autorizado.');
        }

        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'codigo' => 'required|string|max:50|unique:students,codigo',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email',
            'telefono' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'codigo.required' => 'El código del estudiante es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'email.unique' => 'Este email ya está registrado.',
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El email es obligatorio.',
        ]);

        Student::create([
            'codigo' => $validated['codigo'],
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'group_id' => $assignment->student_group_id,
            'estado' => 'activo',
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return redirect()->route('profesor.estudiantes.index')
            ->with('success', 'Estudiante registrado exitosamente.');
    }

    /**
     * Mostrar formulario para editar un estudiante
     */
    public function edit($id)
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $student = Student::with('group')->findOrFail($id);

        // Verificar que el estudiante pertenezca a un grupo del profesor
        $assignment = Assignment::where('student_group_id', $student->group_id)
            ->where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'classroom'])
            ->first();

        if (!$assignment) {
            return redirect()->route('profesor.estudiantes.index')
                ->with('error', 'No tiene permisos para editar este estudiante.');
        }

        return view('profesor.estudiantes.edit', [
            'student' => $student,
            'assignment' => $assignment,
        ]);
    }

    /**
     * Actualizar un estudiante
     */
    public function update(Request $request, $id)
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $student = Student::findOrFail($id);

        // Verificar autorización
        $assignment = Assignment::where('student_group_id', $student->group_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$assignment) {
            return redirect()->route('profesor.estudiantes.index')
                ->with('error', 'No tiene permisos para editar este estudiante.');
        }

        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:students,codigo,' . $id,
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email,' . $id,
            'telefono' => 'nullable|string|max:20',
            'estado' => 'required|in:activo,inactivo,retirado',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $student->update($validated);

        return redirect()->route('profesor.estudiantes.index')
            ->with('success', 'Estudiante actualizado exitosamente.');
    }

    /**
     * Eliminar un estudiante
     */
    public function destroy($id)
    {
        $teacher = $this->currentTeacher();

        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $student = Student::findOrFail($id);

        // Verificar autorización
        $assignment = Assignment::where('student_group_id', $student->group_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$assignment) {
            return redirect()->route('profesor.estudiantes.index')
                ->with('error', 'No tiene permisos para eliminar este estudiante.');
        }

        $student->delete();

        return redirect()->route('profesor.estudiantes.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }
}
