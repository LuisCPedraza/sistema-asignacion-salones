<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\ClassroomAvailability;
use App\Modules\Auth\Models\Role;

class HU6_ClassroomAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_infrastructure_coordinator_can_view_classroom_availabilities()
    {
        $infraRole = Role::where('slug', 'coordinador_infraestructura')->first();
        $user = User::factory()->create(['role_id' => $infraRole->id]);
        $this->actingAs($user);

        $classroom = Classroom::create([
            'name' => 'Aula 101',
            'code' => 'A101',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);

        // Crear disponibilidad directamente sin factory
        $availability = ClassroomAvailability::create([
            'classroom_id' => $classroom->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'is_available' => true,
            'availability_type' => 'regular',
        ]);

        $response = $this->get(route('infraestructura.classrooms.availabilities.index', $classroom));

        $response->assertStatus(200);
        $response->assertSee('Lunes');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_infrastructure_coordinator_can_create_classroom_availability()
    {
        $infraRole = Role::where('slug', 'coordinador_infraestructura')->first();
        $user = User::factory()->create(['role_id' => $infraRole->id]);
        $this->actingAs($user);

        $classroom = Classroom::create([
            'name' => 'Aula 101',
            'code' => 'A101',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);

        $response = $this->post(route('infraestructura.classrooms.availabilities.store', $classroom), [
            'day' => 'monday',
            'start_time' => '08:00',
            'end_time' => '10:00',
            'is_available' => true,
            'availability_type' => 'regular',
            'notes' => 'Disponible para clases'
        ]);

        $response->assertRedirect();
        
        // Verificar en la base de datos (formato TIME puro via casts)
        $this->assertDatabaseHas('classroom_availabilities', [
            'classroom_id' => $classroom->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function availability_requires_valid_time_range()
    {
        $infraRole = Role::where('slug', 'coordinador_infraestructura')->first();
        $user = User::factory()->create(['role_id' => $infraRole->id]);
        $this->actingAs($user);

        $classroom = Classroom::create([
            'name' => 'Aula 101',
            'code' => 'A101',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);

        $response = $this->post(route('infraestructura.classrooms.availabilities.store', $classroom), [
            'day' => 'monday',
            'start_time' => '10:00',
            'end_time' => '08:00', // Hora de fin anterior a inicio
            'is_available' => true,
            'availability_type' => 'regular',
        ]);

        $response->assertSessionHasErrors(['end_time']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function non_infrastructure_coordinator_cannot_access_availabilities()
    {
        $professorRole = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create(['role_id' => $professorRole->id]);
        $this->actingAs($user);

        $classroom = Classroom::create([
            'name' => 'Aula 101',
            'code' => 'A101',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('infraestructura.classrooms.availabilities.index', $classroom));

        $response->assertStatus(403);
    }
}