<?php

namespace Tests\Unit\Modules\Auth; // ← Cambiar el namespace

use Tests\TestCase;
use App\Modules\Auth\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_has_correct_slugs()
    {
        $this->assertEquals('administrador', Role::ADMINISTRADOR);
        $this->assertEquals('coordinador', Role::COORDINADOR);
        // ... resto del test
    }

    public function test_role_can_be_created()
    {
        $role = Role::factory()->create([
            'name' => 'Test Role',
            'slug' => 'test-role',
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Test Role',
            'slug' => 'test-role',
        ]);
    }

    public function test_get_roles_returns_correct_structure()
    {
        $roles = Role::getRoles();
        
        $this->assertIsArray($roles);
        $this->assertArrayHasKey('administrador', $roles);
        $this->assertEquals('Administrador', $roles['administrador']);
    }

    public function test_role_has_users_relationship()
    {
        // Crear un rol usando el factory correcto
        $role = Role::factory()->administrador()->create();
        
        // Crear un usuario asociado a ese rol
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        // Verificar la relación
        $this->assertTrue($role->users()->exists());
        $this->assertEquals(1, $role->users()->count());
        $this->assertEquals($user->id, $role->users()->first()->id);
    }
}