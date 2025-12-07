<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Teacher;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_coordinator_can_view_teachers_index()
    {
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        $response = $this->get(route('gestion-academica.teachers.index'));
        $response->assertStatus(200);
    }

    public function test_coordinator_can_create_teacher()
    {
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        $response = $this->post(route('gestion-academica.teachers.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123456789',
            'specialty' => 'Computer Science',
            'years_experience' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('teachers', ['email' => 'john.doe@example.com']);
    }

    public function test_active_scope_returns_only_active_teachers()
    {
        Teacher::factory()->create(['is_active' => true]);
        Teacher::factory()->create(['is_active' => false]);

        $this->assertEquals(1, Teacher::active()->count());
    }

    public function test_non_coordinator_cannot_access_teachers()
    {
        $professorRole = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create(['role_id' => $professorRole->id]);
        $this->actingAs($user);

        $response = $this->get(route('gestion-academica.teachers.index'));
        $response->assertStatus(403);
    }
}