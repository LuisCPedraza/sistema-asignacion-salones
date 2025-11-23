<?php

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
    }

    public function test_admin_can_access_admin_routes()
    {
        $admin = User::where('email', 'admin@universidad.edu')->first();
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('Panel de Administración');
    }

    public function test_non_admin_cannot_access_admin_routes()
    {
        // Crear un usuario con rol de profesor
        $professorRole = Role::where('slug', 'profesor')->first();
        $professor = User::factory()->create(['role_id' => $professorRole->id]);
        
        $response = $this->actingAs($professor)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_routes()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect('/login');
    }
}