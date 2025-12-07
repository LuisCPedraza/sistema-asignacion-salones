<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\Auth\Models\Role;

class SimpleRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_creation()
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

    public function test_role_constants_are_correct()
    {
        $this->assertEquals('administrador', Role::ADMINISTRADOR);
        $this->assertEquals('profesor', Role::PROFESOR);
        $this->assertEquals('coordinador', Role::COORDINADOR);
    }

    public function test_get_roles_method_returns_array()
    {
        $roles = Role::getRoles();
        
        $this->assertIsArray($roles);
        $this->assertArrayHasKey('administrador', $roles);
        $this->assertEquals('Administrador', $roles['administrador']);
    }
}