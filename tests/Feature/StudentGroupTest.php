<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class StudentGroupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ejecutar seeders para crear roles
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_coordinator_can_view_student_groups_index()
    {
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        $response = $this->get(route('gestion-academica.student-groups.index'));
        $response->assertStatus(200);
    }

    public function test_coordinator_can_create_student_group()
    {
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        $response = $this->post(route('gestion-academica.student-groups.store'), [
            'name' => 'Test Group',
            'level' => 'Bachillerato',
            'student_count' => 30,
            'special_features' => 'Especial',
            'academic_period_id' => null,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('student_groups', ['name' => 'Test Group']);
    }

    public function test_active_scope_returns_only_active_groups()
    {
        // Crear grupos directamente sin depender de factory
        StudentGroup::create([
            'name' => 'Active Group',
            'level' => 'Bachillerato', 
            'student_count' => 25,
            'is_active' => true
        ]);
        
        StudentGroup::create([
            'name' => 'Inactive Group',
            'level' => 'Universitario',
            'student_count' => 30,
            'is_active' => false
        ]);

        $this->assertEquals(1, StudentGroup::active()->count());
    }

    public function test_non_coordinator_cannot_access_student_groups()
    {
        $professorRole = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create(['role_id' => $professorRole->id]);
        $this->actingAs($user);

        $response = $this->get(route('gestion-academica.student-groups.index'));
        $response->assertStatus(403);
    }
}