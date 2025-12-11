<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CoordinatorMiddleware;
use App\Http\Middleware\CheckGuestTeacherAccess;
use App\Modules\Auth\Models\Role;
use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected Role $adminRole;
    protected Role $profesorRole;
    protected Role $coordinadorRole;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear roles una sola vez para todos los tests
        $this->adminRole = Role::firstOrCreate(
            ['slug' => 'administrador'],
            ['name' => 'Administrador', 'description' => 'Administrador del sistema']
        );
        
        $this->profesorRole = Role::firstOrCreate(
            ['slug' => 'profesor'],
            ['name' => 'Profesor', 'description' => 'Profesor regular']
        );
        
        $this->coordinadorRole = Role::firstOrCreate(
            ['slug' => 'coordinador'],
            ['name' => 'Coordinador', 'description' => 'Coordinador académico']
        );
    }

    // ===================== AdminMiddleware Tests =====================

    #[Test]
    public function admin_middleware_redirects_unauthenticated_users(): void
    {
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin', 'GET');
        
        $response = $middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertEquals(302, $response->getStatusCode());
    }

    #[Test]
    public function admin_middleware_blocks_non_admin_users(): void
    {
        $user = User::factory()->create(['role_id' => $this->profesorRole->id]);
        $this->actingAs($user);

        $middleware = new AdminMiddleware();
        $request = Request::create('/admin', 'GET');

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $middleware->handle($request, function () {
            return new Response('OK');
        });
    }

    #[Test]
    public function admin_middleware_allows_admin_users(): void
    {
        $user = User::factory()->create(['role_id' => $this->adminRole->id]);
        $this->actingAs($user);

        $middleware = new AdminMiddleware();
        $request = Request::create('/admin', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    // ===================== CoordinatorMiddleware Tests =====================

    #[Test]
    public function coordinator_middleware_allows_coordinator_users(): void
    {
        $user = User::factory()->create(['role_id' => $this->coordinadorRole->id]);
        $this->actingAs($user);

        $middleware = new CoordinatorMiddleware();
        $request = Request::create('/coordinator', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    // ===================== CheckGuestTeacherAccess Tests =====================

    #[Test]
    public function guest_teacher_middleware_allows_non_guest_teachers(): void
    {
        $user = User::factory()->create(['role_id' => $this->profesorRole->id]);
        $teacher = Teacher::factory()->create([
            'user_id' => $user->id,
            'is_guest' => false,
        ]);
        $this->actingAs($user);

        $middleware = new CheckGuestTeacherAccess();
        $request = Request::create('/profesor', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    #[Test]
    public function guest_teacher_middleware_allows_active_guest_teachers(): void
    {
        $user = User::factory()->create(['role_id' => $this->profesorRole->id]);
        $teacher = Teacher::factory()->create([
            'user_id' => $user->id,
            'is_guest' => true,
            'access_expires_at' => now()->addWeek(),
        ]);
        $this->actingAs($user);

        $middleware = new CheckGuestTeacherAccess();
        $request = Request::create('/profesor', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }
}
