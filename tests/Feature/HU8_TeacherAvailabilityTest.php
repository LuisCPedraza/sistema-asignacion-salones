<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\Auth\Models\Role;

class HU8_TeacherAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function hu8_coordinator_can_view_teacher_availability_page()
    {
        // Given: Un coordinador autenticado
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        // And: Un profesor en la base de datos
        $teacher = Teacher::create([
            'first_name' => 'Carlos',
            'last_name' => 'Mendoza',
            'email' => 'carlos.mendoza@test.edu',
            'specialty' => 'Programación',
            'is_active' => true,
        ]);

        // When: Accede a la página de disponibilidades
        $response = $this->get(route('gestion-academica.teachers.availabilities.index', $teacher));

        // Then: Debe ver la página correctamente
        $response->assertStatus(200);
        $response->assertSee('Disponibilidades de Carlos Mendoza');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function hu8_coordinator_can_create_availability_for_teacher()
    {
        // Given: Un coordinador autenticado
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        // And: Un profesor en la base de datos
        $teacher = Teacher::create([
            'first_name' => 'Ana',
            'last_name' => 'García',
            'email' => 'ana.garcia@test.edu',
            'specialty' => 'Matemáticas',
            'is_active' => true,
        ]);

        // When: Crea una disponibilidad para el profesor
        $response = $this->post(route('gestion-academica.teachers.availabilities.store', $teacher), [
            'day' => 'monday',
            'start_time' => '08:00',
            'end_time' => '10:00',
            'is_available' => true,
            'notes' => 'Disponible para clases teóricas'
        ]);

        // Then: La disponibilidad se crea exitosamente
        $response->assertRedirect();
        
        // Verificar que se guardaron los datos básicos (formato TIME puro via casts)
        $this->assertDatabaseHas('teacher_availabilities', [
            'teacher_id' => $teacher->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'is_available' => true,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function hu8_availability_requires_valid_time_range()
    {
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        $teacher = Teacher::create([
            'first_name' => 'Test',
            'last_name' => 'Teacher',
            'email' => 'test@example.com',
            'specialty' => 'Test',
            'is_active' => true,
        ]);

        // Test: Hora de fin debe ser posterior a hora de inicio
        $response = $this->post(route('gestion-academica.teachers.availabilities.store', $teacher), [
            'day' => 'monday',
            'start_time' => '10:00',
            'end_time' => '08:00', // Inválido
            'is_available' => true,
        ]);

        $response->assertSessionHasErrors(['end_time']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function hu8_teacher_cannot_access_other_teacher_availabilities()
    {
        // Given: Un profesor autenticado
        $teacherRole = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create(['role_id' => $teacherRole->id]);
        $this->actingAs($user);

        // And: Otro profesor en el sistema
        $otherTeacher = Teacher::create([
            'first_name' => 'Otro',
            'last_name' => 'Profesor',
            'email' => 'otro@example.com',
            'specialty' => 'Física',
            'is_active' => true,
        ]);

        // When: Intenta acceder a las disponibilidades del otro profesor
        $response = $this->get(route('gestion-academica.teachers.availabilities.index', $otherTeacher));

        // Then: Debe ser denegado
        $response->assertStatus(403);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function hu8_guest_cannot_access_availabilities()
    {
        $teacher = Teacher::create([
            'first_name' => 'Test',
            'last_name' => 'Teacher',
            'email' => 'test@example.com',
            'specialty' => 'Test',
            'is_active' => true,
        ]);

        // When: Usuario no autenticado intenta acceder
        $response = $this->get(route('gestion-academica.teachers.availabilities.index', $teacher));

        // Then: Debe ser redirigido al login
        $response->assertRedirect(route('login'));
    }
}