<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_check_role()
    {
        // Crear un rol específico primero
        $role = \App\Modules\Auth\Models\Role::factory()->create([
            'slug' => 'administrador',
            'name' => 'Administrador'
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        // Debug: ver qué hay en el usuario y su rol
       //dd($user->toArray(), $user->role ? $user->role->toArray() : 'No role');    

        $this->assertTrue($user->hasRole('administrador'));
        $this->assertFalse($user->hasRole('profesor'));
    }

    public function test_user_can_access_system_when_active()
    {
        // Crear un rol primero
        $role = \App\Modules\Auth\Models\Role::factory()->create(['slug' => 'profesor']);
        
        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $this->assertTrue($user->canAccessSystem());
    }

    public function test_user_cannot_access_system_when_inactive()
    {
        $user = User::factory()->withRole('profesor')->inactive()->create();

        $this->assertFalse($user->canAccessSystem());
    }

    public function test_temporary_access_expiration_check()
    {
        $user = User::factory()->withRole('profesor_invitado')->create([
            'temporary_access_expires_at' => now()->addDays(5),
        ]);

        $this->assertFalse($user->isTemporaryAccessExpired());

        $user->temporary_access_expires_at = now()->subDays(1);
        $this->assertTrue($user->isTemporaryAccessExpired());
    }

    public function test_user_scope_active()
    {
        User::factory()->withRole('profesor')->create(); // Active
        User::factory()->withRole('profesor')->inactive()->create(); // Inactive

        $activeUsers = User::active()->get();
        
        $this->assertCount(1, $activeUsers);
        $this->assertTrue($activeUsers->first()->is_active);
    }
}