<?php

namespace Tests\Unit\Modules\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\Auth\Models\Role;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_has_correct_slugs()
    {
        $this->assertEquals('administrador', Role::ADMINISTRADOR);
        $this->assertEquals('profesor_invitado', Role::PROFESOR_INVITADO);
        $this->assertEquals('coordinador', Role::COORDINADOR);
    }

    public function test_role_can_be_created()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test_role',
            'description' => 'Test Description'
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'test_role',
            'is_active' => true
        ]);
    }

    public function test_get_roles_returns_correct_structure()
    {
        $roles = Role::getRoles();
        
        $this->assertIsArray($roles);
        $this->assertArrayHasKey('administrador', $roles);
        $this->assertEquals('Administrador', $roles['administrador']);
        $this->assertArrayHasKey('profesor', $roles);
        $this->assertEquals('Profesor', $roles['profesor']);
    }

    public function test_role_has_users_relationship()
    {
        $role = Role::factory()->administrator()->create();
        
        $this->assertTrue(method_exists($role, 'users'));
    }
}