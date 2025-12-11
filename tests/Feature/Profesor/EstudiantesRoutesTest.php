<?php

namespace Tests\Feature\Profesor;

use App\Modules\Auth\Models\Role;
use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstudiantesRoutesTest extends TestCase
{
    use RefreshDatabase;

    private User $profesor;
    private User $coordinador;
    private Assignment $assignment;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles
        $roleProfesor = Role::firstOrCreate(
            ['slug' => 'profesor'],
            ['name' => 'Profesor', 'description' => 'Rol de profesor']
        );

        $roleCoordinador = Role::firstOrCreate(
            ['slug' => 'coordinador'],
            ['name' => 'Coordinador', 'description' => 'Rol de coordinador']
        );

        // Crear usuarios
        $this->profesor = User::factory()->create(['email' => 'profesor@test.com']);
        $this->profesor->role_id = $roleProfesor->id;
        $this->profesor->save();

        $this->coordinador = User::factory()->create(['email' => 'coordinador@test.com']);
        $this->coordinador->role_id = $roleCoordinador->id;
        $this->coordinador->save();

        // Crear teacher y asignación mínima para rutas
        $teacher = Teacher::factory()->create([
            'user_id' => $this->profesor->id,
        ]);

        $group = StudentGroup::factory()->create();
        $subject = Subject::factory()->create();
        $classroom = Classroom::factory()->create();

        $this->assignment = Assignment::factory()->create([
            'teacher_id' => $teacher->id,
            'student_group_id' => $group->id,
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
        ]);
    }

    /** @test */
    public function estudiantes_index_route_is_protected_by_auth()
    {
        $response = $this->get(route('profesor.estudiantes.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function estudiantes_create_route_is_protected_by_auth()
    {
        $response = $this->get(route('profesor.estudiantes.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function estudiantes_store_route_is_protected_by_auth()
    {
        $response = $this->post(route('profesor.estudiantes.store'), []);
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function estudiantes_edit_route_is_protected_by_auth()
    {
        $response = $this->get(route('profesor.estudiantes.edit', 1));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function estudiantes_update_route_is_protected_by_auth()
    {
        $response = $this->put(route('profesor.estudiantes.update', 1), []);
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function estudiantes_destroy_route_is_protected_by_auth()
    {
        $response = $this->delete(route('profesor.estudiantes.destroy', 1));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function only_profesor_role_can_access_estudiantes_routes()
    {
        // Coordinador no debe poder acceder
        $response = $this->actingAs($this->coordinador)
            ->get(route('profesor.estudiantes.index'));
        
        $response->assertStatus(403);
    }

    /** @test */
    public function profesor_can_access_all_estudiantes_routes()
    {
        // Index
        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.index'));
        $response->assertStatus(200);

        // Create (sin assignment_id redirige con error, pero no 403)
        $response = $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.create'));
        $response->assertRedirect();
    }

    /** @test */
    public function all_estudiantes_routes_are_named_correctly()
    {
        $this->assertTrue(route('profesor.estudiantes.index') !== null);
        $this->assertTrue(route('profesor.estudiantes.create') !== null);
        $this->assertTrue(route('profesor.estudiantes.store') !== null);
        $this->assertTrue(route('profesor.estudiantes.edit', 1) !== null);
        $this->assertTrue(route('profesor.estudiantes.update', 1) !== null);
        $this->assertTrue(route('profesor.estudiantes.destroy', 1) !== null);
    }

    /** @test */
    public function estudiantes_routes_use_correct_http_methods()
    {
        // GET routes
        $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.index'))
            ->assertStatus(200);

        $this->actingAs($this->profesor)
            ->get(route('profesor.estudiantes.create'))
            ->assertRedirect(); // Redirige porque falta assignment_id

        // POST route
        $this->actingAs($this->profesor)
            ->post(route('profesor.estudiantes.store'), [
                'assignment_id' => $this->assignment->id,
            ])
            ->assertSessionHasErrors(); // Valida pero acepta POST

        // PUT route
        $this->actingAs($this->profesor)
            ->put(route('profesor.estudiantes.update', 999), [])
            ->assertStatus(404); // Not found es correcto, acepta PUT

        // DELETE route
        $this->actingAs($this->profesor)
            ->delete(route('profesor.estudiantes.destroy', 999))
            ->assertStatus(404); // Not found es correcto, acepta DELETE
    }

    /** @test */
    public function estudiantes_routes_have_correct_prefixes()
    {
        $this->assertStringContainsString('/profesor/estudiantes', route('profesor.estudiantes.index'));
        $this->assertStringContainsString('/profesor/estudiantes/crear', route('profesor.estudiantes.create'));
        $this->assertStringContainsString('/profesor/estudiantes/guardar', route('profesor.estudiantes.store'));
        $this->assertStringContainsString('/profesor/estudiantes/editar/', route('profesor.estudiantes.edit', 1));
        $this->assertStringContainsString('/profesor/estudiantes/actualizar/', route('profesor.estudiantes.update', 1));
        $this->assertStringContainsString('/profesor/estudiantes/eliminar/', route('profesor.estudiantes.destroy', 1));
    }
}
