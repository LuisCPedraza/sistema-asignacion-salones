<?php

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Auth\Models\Role;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(TestCase::class);

function crearContextoProfesor(): array
{
    $role = Role::firstOrCreate(
        ['slug' => Role::PROFESOR],
        ['name' => 'Profesor', 'description' => 'Profesor', 'is_active' => true]
    );
    $user = User::factory()->create(['role_id' => $role->id]);
    $teacher = Teacher::factory()->create(['user_id' => $user->id]);
    $group = StudentGroup::factory()->create(['student_count' => 3]);
    $subject = Subject::factory()->create();
    $classroom = Classroom::factory()->create();

    $assignment = Assignment::factory()->create([
        'student_group_id' => $group->id,
        'teacher_id' => $teacher->id,
        'classroom_id' => $classroom->id,
        'subject_id' => $subject->id,
        'day' => 'monday',
        'start_time' => '08:00',
        'end_time' => '10:00',
    ]);

    $students = Student::factory()
        ->count(3)
        ->state(fn () => ['group_id' => $group->id, 'estado' => 'activo'])
        ->create();

    return compact('user', 'teacher', 'group', 'assignment', 'students');
}

it('guarda y actualiza asistencias por fecha', function () {
    $ctx = crearContextoProfesor();
    actingAs($ctx['user']);

    $fecha = Carbon::today()->toDateString();
    $students = $ctx['students'];

    $payload = [
        'fecha' => $fecha,
        'asistencias' => [
            $students[0]->id => 'presente',
            $students[1]->id => 'ausente',
            $students[2]->id => 'tardanza',
        ],
    ];

    post(route('profesor.asistencias.guardar', $ctx['assignment']->id), $payload)
        ->assertRedirect(route('profesor.asistencias.index'));

    expect(
        Attendance::where('assignment_id', $ctx['assignment']->id)
            ->where('student_id', $students[0]->id)
            ->whereDate('fecha', $fecha)
            ->where('status', 'presente')
            ->exists()
    )->toBeTrue();

    // Actualiza mismos registros (único por fecha)
    $payload['asistencias'][$students[0]->id] = 'ausente';
    $payload['asistencias'][$students[1]->id] = 'presente';

    post(route('profesor.asistencias.guardar', $ctx['assignment']->id), $payload)
        ->assertRedirect(route('profesor.asistencias.index'));

    expect(
        Attendance::where('assignment_id', $ctx['assignment']->id)
            ->where('student_id', $students[0]->id)
            ->whereDate('fecha', $fecha)
            ->where('status', 'ausente')
            ->exists()
    )->toBeTrue();

    $this->assertSame(3, Attendance::where('assignment_id', $ctx['assignment']->id)->whereDate('fecha', $fecha)->count());
});

it('muestra historial con promedios reales', function () {
    $ctx = crearContextoProfesor();
    actingAs($ctx['user']);
    $students = $ctx['students'];
    $assignment = $ctx['assignment'];

    $fecha1 = Carbon::today()->toDateString();
    $fecha2 = Carbon::today()->subDay()->toDateString();

    Attendance::create([
        'assignment_id' => $assignment->id,
        'student_id' => $students[0]->id,
        'fecha' => $fecha1,
        'status' => 'presente',
        'taken_by' => $ctx['user']->id,
    ]);
    Attendance::create([
        'assignment_id' => $assignment->id,
        'student_id' => $students[1]->id,
        'fecha' => $fecha1,
        'status' => 'presente',
        'taken_by' => $ctx['user']->id,
    ]);
    Attendance::create([
        'assignment_id' => $assignment->id,
        'student_id' => $students[2]->id,
        'fecha' => $fecha1,
        'status' => 'ausente',
        'taken_by' => $ctx['user']->id,
    ]);

    Attendance::create([
        'assignment_id' => $assignment->id,
        'student_id' => $students[0]->id,
        'fecha' => $fecha2,
        'status' => 'ausente',
        'taken_by' => $ctx['user']->id,
    ]);
    Attendance::create([
        'assignment_id' => $assignment->id,
        'student_id' => $students[1]->id,
        'fecha' => $fecha2,
        'status' => 'presente',
        'taken_by' => $ctx['user']->id,
    ]);
    Attendance::create([
        'assignment_id' => $assignment->id,
        'student_id' => $students[2]->id,
        'fecha' => $fecha2,
        'status' => 'ausente',
        'taken_by' => $ctx['user']->id,
    ]);

    $resp = get(route('profesor.asistencias.historial', $assignment->id));
    $resp->assertOk();
    $resp->assertSee('Historial de Asistencias');
    $resp->assertSee('50'); // promedio aproximado entre 66.7% y 33.3%
});
