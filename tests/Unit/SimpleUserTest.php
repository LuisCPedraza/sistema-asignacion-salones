<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class SimpleUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ejecutar seeders
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
    }

    public function test_user_creation()
    {
        $role = Role::where('slug', 'administrador')->first();
        
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role_id' => $role->id
        ]);
    }

    public function test_admin_user_exists()
    {
        $user = User::where('email', 'admin@universidad.edu')->first();
        
        $this->assertNotNull($user, 'El usuario admin debería existir después de ejecutar los seeders');
        $this->assertEquals('Administrador Principal', $user->name);
    }

    public function test_user_has_role_relationship()
    {
        $user = User::where('email', 'admin@universidad.edu')->first();
        
        $this->assertNotNull($user, 'Usuario admin no encontrado');
        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals('administrador', $user->role->slug);
    }

    public function test_user_can_check_role()
    {
        $user = User::where('email', 'admin@universidad.edu')->first();
        
        $this->assertTrue($user->hasRole('administrador'));
        $this->assertFalse($user->hasRole('profesor'));
    }

    public function test_active_user_can_access_system()
    {
        $user = User::where('email', 'admin@universidad.edu')->first();
        
        $this->assertTrue($user->canAccessSystem());
    }
}