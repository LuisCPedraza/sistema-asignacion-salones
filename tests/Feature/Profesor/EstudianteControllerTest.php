<?php

namespace Tests\Feature\Profesor;

use App\Models\Student;
use App\Modules\Auth\Models\Role;
use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Asignacion\Models\Assignment;
use App\Models\Subject;
use App\Modules\Infraestructura\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstudianteControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $profesor;
    private Teacher $teacher;
    private StudentGroup $group;
    private Assignment $assignment;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear rol de profesor
        $roleProfesor = Role::firstOrCreate(
            ['slug' => 'profesor'],
            ['name' => 'Profesor', 'description' => 'Rol de profesor']
        );

        // Crear usuario profesor
        $this->profesor = User::factory()->create([
            'name' => 'Juan Profesor',
            'email' => 'profesor@test.com',
        ]);
        $this->profesor->role_id = $roleProfesor->id;
        $this->profesor->save();

        // Crear teacher asociado
        $this->teacher = Teacher::factory()->create([
            'user_id' => $this->profesor->id,
            'first_name' => 'Juan',
            'last_name' => 'Profesor',
        ]);

        // Crear grupo
        $this->group = StudentGroup::factory()->create([
            'name' => 'Grupo A',
        ]);

        // Crear assignment
        $this->assignment = Assignment::factory()->create([
            'teacher_id' => $this->teacher->id,
            'student_group_id' => $this->group->id,
            'subject_id' => Subject::factory()->create(['name' => 'Matemáticas'])->id,
            'classroom_id' => Classroom::factory()->create()->id,
        ]);
    }

    /** @test */
    public function it_displays_students_index_page()
    {
        // Crear algunos estudiantes
        Student::factory()->count(3)->create([
            'group_id' => $this->group->id,
        ]);

        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.index'));

        $response->assertStatus(200);
        $response->assertSee('Gestión de Estudiantes');
        $response->assertSee('Matemáticas');
        $response->assertSee('Grupo A');
    }

    /** @test */
    public function it_requires_authentication_to_access_students()
    {
        $response = $this->get(route('profesor.estudiantes.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_displays_create_student_form()
    {
        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.create', ['assignment_id' => $this->assignment->id]));

        $response->assertStatus(200);
        $response->assertSee('Nuevo Estudiante');
        $response->assertSee('Código del Estudiante');
        $response->assertSee('Matemáticas');
    }

    /** @test */
    public function it_requires_assignment_id_to_create_student()
    {
        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.create'));

        $response->assertRedirect(route('profesor.estudiantes.index'));
        $response->assertSessionHas('error', 'Debe seleccionar un grupo.');
    }

    /** @test */
    public function it_can_store_a_new_student()
    {
        $studentData = [
            'assignment_id' => $this->assignment->id,
            'codigo' => 'EST-2024-001',
            'nombre' => 'María',
            'apellido' => 'González',
            'email' => 'maria.gonzalez@test.com',
            'telefono' => '555-1234',
            'observaciones' => 'Estudiante regular',
        ];

        $response = $this->actingAs($this->profesor)
            ->post(route('profesor.estudiantes.store'), $studentData);

        $response->assertRedirect(route('profesor.estudiantes.index'));
        $response->assertSessionHas('success', 'Estudiante registrado exitosamente.');

        $this->assertDatabaseHas('students', [
            'codigo' => 'EST-2024-001',
            'nombre' => 'María',
            'apellido' => 'González',
            'email' => 'maria.gonzalez@test.com',
            'group_id' => $this->group->id,
            'estado' => 'activo',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->actingAs($this->profesor)
            ->post(route('profesor.estudiantes.store'), [
                'assignment_id' => $this->assignment->id,
                // Faltan campos requeridos
            ]);

        $response->assertSessionHasErrors(['codigo', 'nombre', 'apellido', 'email']);
    }

    /** @test */
    public function it_validates_unique_codigo_when_storing()
    {
        Student::factory()->create([
            'codigo' => 'EST-001',
            'group_id' => $this->group->id,
        ]);

        $response = $this->actingAs($this->profesor)
            ->post(route('profesor.estudiantes.store'), [
                'assignment_id' => $this->assignment->id,
                'codigo' => 'EST-001', // Duplicado
                'nombre' => 'Pedro',
                'apellido' => 'Ramírez',
                'email' => 'pedro@test.com',
            ]);

        $response->assertSessionHasErrors(['codigo']);
    }

    /** @test */
    public function it_validates_unique_email_when_storing()
    {
        Student::factory()->create([
            'email' => 'existente@test.com',
            'group_id' => $this->group->id,
        ]);

        $response = $this->actingAs($this->profesor)
            ->post(route('profesor.estudiantes.store'), [
                'assignment_id' => $this->assignment->id,
                'codigo' => 'EST-NEW',
                'nombre' => 'Pedro',
                'apellido' => 'Ramírez',
                'email' => 'existente@test.com', // Duplicado
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_displays_edit_student_form()
    {
        $student = Student::factory()->create([
            'group_id' => $this->group->id,
            'nombre' => 'Carlos',
            'apellido' => 'Martínez',
        ]);

        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.edit', $student->id));

        $response->assertStatus(200);
        $response->assertSee('Editar Estudiante');
        $response->assertSee('Carlos');
        $response->assertSee('Martínez');
    }

    /** @test */
    public function it_can_update_a_student()
    {
        $student = Student::factory()->create([
            'group_id' => $this->group->id,
            'codigo' => 'EST-OLD',
            'nombre' => 'Nombre Viejo',
        ]);

        $updateData = [
            'codigo' => 'EST-NEW',
            'nombre' => 'Nombre Nuevo',
            'apellido' => 'Apellido Nuevo',
            'email' => 'nuevo@test.com',
            'telefono' => '555-9999',
            'estado' => 'inactivo',
            'observaciones' => 'Actualizado',
        ];

        $response = $this->actingAs($this->profesor)
            ->put(route('profesor.estudiantes.update', $student->id), $updateData);

        $response->assertRedirect(route('profesor.estudiantes.index'));
        $response->assertSessionHas('success', 'Estudiante actualizado exitosamente.');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'codigo' => 'EST-NEW',
            'nombre' => 'Nombre Nuevo',
            'estado' => 'inactivo',
        ]);
    }

    /** @test */
    public function it_validates_unique_codigo_when_updating_except_self()
    {
        $student1 = Student::factory()->create([
            'codigo' => 'EST-001',
            'group_id' => $this->group->id,
        ]);

        $student2 = Student::factory()->create([
            'codigo' => 'EST-002',
            'group_id' => $this->group->id,
        ]);

        // Intentar actualizar student2 con el código de student1
        $response = $this->actingAs($this->profesor)
            ->put(route('profesor.estudiantes.update', $student2->id), [
                'codigo' => 'EST-001', // Ya existe en student1
                'nombre' => 'Test',
                'apellido' => 'Test',
                'email' => 'test@test.com',
                'estado' => 'activo',
            ]);

        $response->assertSessionHasErrors(['codigo']);

        // Pero debe permitir mantener su propio código
        $response = $this->actingAs($this->profesor)
            ->put(route('profesor.estudiantes.update', $student2->id), [
                'codigo' => 'EST-002', // Su propio código
                'nombre' => 'Test Updated',
                'apellido' => 'Test',
                'email' => 'test2@test.com',
                'estado' => 'activo',
            ]);

        $response->assertSessionDoesntHaveErrors(['codigo']);
    }

    /** @test */
    public function it_can_delete_a_student()
    {
        $student = Student::factory()->create([
            'group_id' => $this->group->id,
        ]);

        $response = $this->actingAs($this->profesor)
            ->delete(route('profesor.estudiantes.destroy', $student->id));

        $response->assertRedirect(route('profesor.estudiantes.index'));
        $response->assertSessionHas('success', 'Estudiante eliminado exitosamente.');

        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }

    /** @test */
    public function it_prevents_unauthorized_teacher_from_editing_student()
    {
        // Crear otro profesor
        $otroProfesor = User::factory()->create();
        $otroProfesor->role_id = Role::where('slug', 'profesor')->first()->id;
        $otroProfesor->save();

        $otroTeacher = Teacher::factory()->create([
            'user_id' => $otroProfesor->id,
        ]);

        // Crear grupo y assignment para otro profesor
        $otroGroup = StudentGroup::factory()->create();
        Assignment::factory()->create([
            'teacher_id' => $otroTeacher->id,
            'student_group_id' => $otroGroup->id,
        ]);

        $student = Student::factory()->create([
            'group_id' => $otroGroup->id,
        ]);

        // Intentar editar con el primer profesor (no autorizado)
        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.edit', $student->id));

        $response->assertRedirect(route('profesor.estudiantes.index'));
        $response->assertSessionHas('error', 'No tiene permisos para editar este estudiante.');
    }

    /** @test */
    public function it_prevents_unauthorized_teacher_from_deleting_student()
    {
        // Crear otro profesor
        $otroProfesor = User::factory()->create();
        $otroProfesor->role_id = Role::where('slug', 'profesor')->first()->id;
        $otroProfesor->save();

        $otroTeacher = Teacher::factory()->create([
            'user_id' => $otroProfesor->id,
        ]);

        $otroGroup = StudentGroup::factory()->create();
        Assignment::factory()->create([
            'teacher_id' => $otroTeacher->id,
            'student_group_id' => $otroGroup->id,
        ]);

        $student = Student::factory()->create([
            'group_id' => $otroGroup->id,
        ]);

        $response = $this->actingAs($this->profesor)
            ->delete(route('profesor.estudiantes.destroy', $student->id));

        $response->assertRedirect(route('profesor.estudiantes.index'));
        $response->assertSessionHas('error', 'No tiene permisos para eliminar este estudiante.');

        // El estudiante no debe haber sido eliminado
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
        ]);
    }

    /** @test */
    public function it_shows_empty_state_when_no_students()
    {
        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.index'));

        $response->assertStatus(200);
        $response->assertSee('No hay estudiantes registrados en este grupo');
    }

    /** @test */
    public function it_groups_students_by_subject_in_index()
    {
        // Crear otra materia y assignment
        $subject2 = Subject::factory()->create(['name' => 'Física']);
        $group2 = StudentGroup::factory()->create(['name' => 'Grupo B']);
        
        Assignment::factory()->create([
            'teacher_id' => $this->teacher->id,
            'student_group_id' => $group2->id,
            'subject_id' => $subject2->id,
        ]);

        Student::factory()->count(2)->create(['group_id' => $this->group->id]);
        Student::factory()->count(3)->create(['group_id' => $group2->id]);

        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.index'));

        $response->assertStatus(200);
        $response->assertSee('Matemáticas');
        $response->assertSee('Física');
        $response->assertSee('2 estudiante(s)');
        $response->assertSee('3 estudiante(s)');
    }
}
