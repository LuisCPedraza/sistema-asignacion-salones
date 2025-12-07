<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Building;
use App\Modules\Auth\Models\Role;

class HU5_ClassroomTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function infrastructure_coordinator_can_view_classrooms_index()
    {
        $infraRole = Role::where('slug', 'coordinador_infraestructura')->first();
        $user = User::factory()->create(['role_id' => $infraRole->id]);
        $this->actingAs($user);

        $response = $this->get(route('infraestructura.classrooms.index'));

        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function infrastructure_coordinator_can_create_classroom()
    {
        $infraRole = Role::where('slug', 'coordinador_infraestructura')->first();
        $user = User::factory()->create(['role_id' => $infraRole->id]);
        $this->actingAs($user);

        $building = Building::create([
            'name' => 'Edificio A',
            'code' => 'EA',
            'location' => 'Campus Central',
            'floors' => 3,
            'is_active' => true,
        ]);

        $response = $this->post(route('infraestructura.classrooms.store'), [
            'name' => 'Aula 101',
            'code' => 'A101',
            'capacity' => 30,
            'building_id' => $building->id,
            'floor' => 1,
            'type' => 'aula',
            'resources' => ['proyector', 'pizarra_inteligente'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('classrooms', ['code' => 'A101']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function active_scope_returns_only_active_classrooms()
    {
        Classroom::create([
            'name' => 'Aula Activa',
            'code' => 'A101',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);
        Classroom::create([
            'name' => 'Aula Inactiva',
            'code' => 'A102',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => false,
        ]);

        $this->assertEquals(1, Classroom::active()->count());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function non_infrastructure_coordinator_cannot_access_classrooms()
    {
        $professorRole = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create(['role_id' => $professorRole->id]);
        $this->actingAs($user);

        $response = $this->get(route('infraestructura.classrooms.index'));

        $response->assertStatus(403);
    }
}