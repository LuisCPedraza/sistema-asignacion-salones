<?php

use App\Models\Activity;
use App\Models\ActivityGrade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Auth\Models\Role;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use Carbon\Carbon;
use Tests\TestCase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

uses(TestCase::class);

function contextoProfesorActividad(): array
{
    $role = Role::firstOrCreate(
        ['slug' => Role::PROFESOR],
        ['name' => 'Profesor', 'description' => 'Profesor', 'is_active' => true]
    );
    $user = User::factory()->create(['role_id' => $role->id]);
    $teacher = Teacher::factory()->create(['user_id' => $user->id]);
    $group = StudentGroup::factory()->create();
    $subject = Subject::factory()->create();
    $classroom = Classroom::factory()->create();

    $assignment = Assignment::factory()->create([
        'student_group_id' => $group->id,
        'teacher_id' => $teacher->id,
        'classroom_id' => $classroom->id,
        'subject_id' => $subject->id,
        'day' => 'tuesday',
        'start_time' => '09:00',
        'end_time' => '11:00',
    ]);

    $students = Student::factory()
        ->count(2)
        ->state(fn () => ['group_id' => $group->id, 'estado' => 'activo'])
        ->create();

    return compact('user', 'assignment', 'students');
}

it('crea una actividad para el curso del profesor', function () {
    $ctx = contextoProfesorActividad();
    actingAs($ctx['user']);

    $payload = [
        'assignment_id' => $ctx['assignment']->id,
        'title' => 'Proyecto 1',
        'description' => 'Entrega inicial',
        'due_date' => Carbon::today()->toDateString(),
        'max_score' => 100,
    ];

    post(route('profesor.actividades.store'), $payload)
        ->assertRedirect(route('profesor.actividades.index'));

    assertDatabaseHas('activities', [
        'assignment_id' => $ctx['assignment']->id,
        'title' => 'Proyecto 1',
        'max_score' => 100,
    ]);
});

it('guarda calificaciones por estudiante', function () {
    $ctx = contextoProfesorActividad();
    actingAs($ctx['user']);

    $activity = Activity::factory()->create([
        'assignment_id' => $ctx['assignment']->id,
        'title' => 'Tarea 1',
        'max_score' => 100,
        'due_date' => Carbon::today()->toDateString(),
    ]);

    $payload = [
        'grades' => [
            $ctx['students'][0]->id => 95,
            $ctx['students'][1]->id => 87.5,
        ],
        'feedback' => [
            $ctx['students'][0]->id => 'Muy bien',
            $ctx['students'][1]->id => 'Revisar ejercicios 2 y 3',
        ],
    ];

    post(route('profesor.actividades.guardar-calificaciones', $activity->id), $payload)
        ->assertRedirect(route('profesor.actividades.index'));

    assertDatabaseHas('activity_grades', [
        'activity_id' => $activity->id,
        'student_id' => $ctx['students'][0]->id,
        'score' => 95,
    ]);

    $this->assertSame(2, ActivityGrade::where('activity_id', $activity->id)->count());
});
