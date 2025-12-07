<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_users_index()
    {
        $adminRole = Role::where('slug', 'administrador')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->actingAs($admin);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_create_user()
    {
        $adminRole = Role::where('slug', 'administrador')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->actingAs($admin);

        $professorRole = Role::where('slug', 'profesor')->first();

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@universidad.edu',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $professorRole->id,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'nuevo@universidad.edu']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function non_admin_cannot_access_admin_routes()
    {
        $professorRole = Role::where('slug', 'profesor')->first();
        $professor = User::factory()->create(['role_id' => $professorRole->id]);
        $this->actingAs($professor);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(403);
    }
}