<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_user_can_check_role()
    {
        $adminRole = Role::where('slug', 'administrador')->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $this->assertTrue($user->hasRole('administrador'));
        $this->assertFalse($user->hasRole('coordinador'));
    }

    public function test_user_can_access_system_when_active()
    {
        $professorRole = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create([
            'role_id' => $professorRole->id,
            'is_active' => true
        ]);

        $this->assertTrue($user->canAccessSystem());
    }

    public function test_user_cannot_access_system_when_inactive()
    {
        $professorRole = Role::where('slug', 'profesor')->first();
        $user = User::factory()->create([
            'role_id' => $professorRole->id,
            'is_active' => false
        ]);

        $this->assertFalse($user->canAccessSystem());
    }

    public function test_temporary_access_expiration_check()
    {
        $user = User::factory()->create([
            'is_active' => true,
            'temporary_access_expires_at' => now()->subDay()
        ]);

        $this->assertTrue($user->isTemporaryAccessExpired());
    }

    public function test_user_scope_active()
    {
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        $this->assertEquals(1, User::active()->count());
    }
}