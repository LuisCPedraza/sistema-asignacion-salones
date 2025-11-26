<?php

namespace Tests\Unit\Modules\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Auth\Models\Role;
use App\Models\User;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_role_has_correct_slugs()
    {
        $this->assertEquals('administrador', Role::ADMINISTRADOR);
        $this->assertEquals('coordinador', Role::COORDINADOR);
    }

    public function test_role_can_be_created()
    {
        // Usar firstOrCreate para evitar conflictos
        $role = Role::firstOrCreate(
            ['slug' => 'test_unique_role'],
            [
                'name' => 'Test Role', 
                'description' => 'Test Description',
                'is_active' => true
            ]
        );

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('test_unique_role', $role->slug);
    }

    public function test_get_roles_returns_correct_structure()
    {
        $roles = Role::getRoles();
        $this->assertIsArray($roles);
        $this->assertArrayHasKey('administrador', $roles);
    }

    public function test_role_has_users_relationship()
    {
        $role = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertTrue($role->users->contains($user));
    }
}