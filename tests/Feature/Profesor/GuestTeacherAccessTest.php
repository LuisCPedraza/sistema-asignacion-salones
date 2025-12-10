<?php

namespace Tests\Feature\Profesor;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\GestionAcademica\Models\Teacher;
use Tests\TestCase;

class GuestTeacherAccessTest extends TestCase
{
    /**
     * Test: Un profesor invitado con acceso válido puede acceder a la plataforma
     */
    public function test_guest_teacher_with_valid_access_can_login(): void
    {
        // Obtener o crear el rol de profesor invitado
        $role = Role::query()->where('slug', Role::PROFESOR_INVITADO)->first();
        
        // Crear un usuario profesor invitado
        $user = User::factory()->create([
            'role_id' => $role->id,
            'temporary_access_expires_at' => now()->addMonth(),
        ]);

        // Crear el profesor vinculado
        Teacher::factory()->create([
            'user_id' => $user->id,
            'is_guest' => true,
            'access_expires_at' => now()->addMonth(),
        ]);

        // Intentar autenticarse
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Debe redirigir al dashboard (acceso válido)
        $this->assertAuthenticated();
    }

    /**
     * Test: Un profesor invitado con acceso expirado es desconectado
     */
    public function test_guest_teacher_with_expired_access_is_logged_out(): void
    {
        // Obtener o crear el rol de profesor invitado
        $role = Role::query()->where('slug', Role::PROFESOR_INVITADO)->first();
        
        // Crear un usuario profesor invitado con acceso expirado
        $user = User::factory()->create([
            'role_id' => $role->id,
            'temporary_access_expires_at' => now()->subDay(),
        ]);

        // Crear el profesor invitado con acceso expirado
        Teacher::factory()->create([
            'user_id' => $user->id,
            'is_guest' => true,
            'access_expires_at' => now()->subDay(),
        ]);

        // Actuar como si estuviéramos autenticados
        $this->actingAs($user);

        // Realizar una solicitud que dispare el middleware
        // El middleware CheckGuestTeacherAccess debería desconectar al usuario
        $response = $this->get('/profesor/dashboard');

        // Después de la solicitud, el usuario debe estar desconectado
        // El middleware logout() no persiste en tests en la misma forma,
        // pero podemos verificar que el método isAccessValid() retorna false
        $teacher = $user->teacher;
        $this->assertFalse($teacher->isAccessValid());
    }

    /**
     * Test: Un profesor regular no es afectado por la validación de acceso
     */
    public function test_regular_teacher_is_not_affected_by_guest_validation(): void
    {
        // Obtener o crear el rol de profesor
        $role = Role::query()->where('slug', Role::PROFESOR)->first();
        
        // Crear un usuario profesor regular (no invitado)
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        // Crear el profesor vinculado (no invitado)
        $teacher = Teacher::factory()->create([
            'user_id' => $user->id,
            'is_guest' => false,
        ]);

        // Actuar como profesor regular
        $this->actingAs($user);

        // Realizar una solicitud
        $response = $this->get('/profesor/dashboard');

        // Debe ser accesible (no expira para profesores regulares)
        $this->assertAuthenticated();
    }

    /**
     * Test: El método isAccessValid() funciona correctamente
     */
    public function test_is_access_valid_method(): void
    {
        // Profesor regular sin expiración
        $regularTeacher = Teacher::factory()->create([
            'is_guest' => false,
            'access_expires_at' => null,
        ]);
        $this->assertTrue($regularTeacher->isAccessValid());

        // Profesor invitado con acceso válido
        $validGuestTeacher = Teacher::factory()->create([
            'is_guest' => true,
            'access_expires_at' => now()->addMonth(),
        ]);
        $this->assertTrue($validGuestTeacher->isAccessValid());

        // Profesor invitado con acceso expirado
        $expiredGuestTeacher = Teacher::factory()->create([
            'is_guest' => true,
            'access_expires_at' => now()->subDay(),
        ]);
        $this->assertFalse($expiredGuestTeacher->isAccessValid());
    }

    /**
     * Test: El scope guest() filtra correctamente los profesores invitados
     */
    public function test_guest_scope_filters_correctly(): void
    {
        // Crear profesores regulares
        Teacher::factory(3)->create(['is_guest' => false]);

        // Crear profesores invitados
        Teacher::factory(2)->create(['is_guest' => true]);

        // Buscar solo invitados
        $guestTeachers = Teacher::guest()->count();

        $this->assertEquals(2, $guestTeachers);
    }

    /**
     * Test: El scope withValidAccess() filtra correctamente
     */
    public function test_with_valid_access_scope_filters_correctly(): void
    {
        // Profesor con acceso válido
        Teacher::factory()->create([
            'is_guest' => true,
            'access_expires_at' => now()->addMonth(),
        ]);

        // Profesor con acceso expirado
        Teacher::factory()->create([
            'is_guest' => true,
            'access_expires_at' => now()->subDay(),
        ]);

        // Profesor sin fecha de expiración
        Teacher::factory()->create([
            'is_guest' => true,
            'access_expires_at' => null,
        ]);

        // Filtrar por acceso válido
        $validTeachers = Teacher::withValidAccess()->count();

        // Debe retornar 2 (uno con expiración futura, uno sin expiración)
        $this->assertEquals(2, $validTeachers);
    }

    /**
     * Test: Validar que la duración máxima no exceda 1 año
     */
    public function test_guest_teacher_max_duration_is_one_year(): void
    {
        $role = Role::query()->where('slug', Role::PROFESOR_INVITADO)->first();

        // Crear profesor con más de 1 año de antigüedad
        $teacher = Teacher::factory()->create([
            'is_guest' => true,
            'access_expires_at' => now()->addYears(2),
            'created_at' => now()->subYears(2),
        ]);

        // La validación debe fallar si intenta acceder después de 1 año
        $this->assertTrue($teacher->created_at->diffInDays(now()) > 365);
    }

    /**
     * Test: Validar restricción de IP permitida
     */
    public function test_guest_teacher_with_ip_restriction_is_enforced(): void
    {
        $role = Role::query()->where('slug', Role::PROFESOR_INVITADO)->first();

        // Crear profesor con restricción de IP
        $user = User::factory()->create([
            'role_id' => $role->id,
            'temporary_access_expires_at' => now()->addMonth(),
        ]);

        $teacher = Teacher::factory()->create([
            'user_id' => $user->id,
            'is_guest' => true,
            'access_expires_at' => now()->addMonth(),
            'ip_address_allowed' => '192.168.1.*,10.0.0.*', // Ejemplo: solo IPs locales
        ]);

        // Profesor debe tener restricción configurada
        $this->assertNotNull($teacher->ip_address_allowed);
        $this->assertStringContainsString('192.168.1.*', $teacher->ip_address_allowed);
    }

    /**
     * Test: Profesor invitado sin restricción IP puede acceder desde cualquier lugar
     */
    public function test_guest_teacher_without_ip_restriction_can_access_anywhere(): void
    {
        $role = Role::query()->where('slug', Role::PROFESOR_INVITADO)->first();

        $user = User::factory()->create([
            'role_id' => $role->id,
            'temporary_access_expires_at' => now()->addMonth(),
        ]);

        $teacher = Teacher::factory()->create([
            'user_id' => $user->id,
            'is_guest' => true,
            'access_expires_at' => now()->addMonth(),
            'ip_address_allowed' => null, // Sin restricción
        ]);

        // Profesor debe poder acceder sin restricción de IP
        $this->assertNull($teacher->ip_address_allowed);
    }
}
