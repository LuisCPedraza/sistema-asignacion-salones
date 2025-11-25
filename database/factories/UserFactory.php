<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // ← AGREGAR ESTA LÍNEA
use App\Models\User;
use App\Modules\Auth\Models\Role;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role_id' => Role::factory(),
            'is_active' => true,
            'temporary_access' => false,
            'access_expires_at' => null,
            'temporary_access_expires_at' => null,
            'remember_token' => Str::random(10), // ← Ahora Str está disponible
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    // ← AGREGAR ESTE MÉTODO FALTANTE
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    // Estado para usuarios pendientes de aprobación
    public function pending()
    {
        return $this->state([
            'role_id' => null,
            'is_active' => false,
            'temporary_access' => false,
            'access_expires_at' => null,
        ]);
    }

    // Estado para usuarios activos con rol
// Estado para usuarios activos con rol
    public function withRole($roleSlug)
    {
        return $this->state(function (array $attributes) use ($roleSlug) {
            // Buscar el rol o crear uno si no existe
            $role = Role::where('slug', $roleSlug)->first();
            
            if (!$role) {
                // Crear el rol si no existe
                $role = Role::factory()->create([
                    'slug' => $roleSlug,
                    'name' => ucfirst($roleSlug),
                ]);
            }
            
            return [
                'role_id' => $role->id,
                'is_active' => true,
            ];
        });
    }
}
