<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        // Obtener un rol existente o crear uno por defecto
        $role = \App\Modules\Auth\Models\Role::inRandomOrder()->first();
        
        if (!$role) {
            $role = \App\Modules\Auth\Models\Role::factory()->create([
                'name' => 'Profesor',
                'slug' => 'profesor',
                'description' => 'Rol por defecto para testing'
            ]);
        }

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'is_active' => true,
            'remember_token' => Str::random(10),
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

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    public function temporary()
    {
        return $this->state(function (array $attributes) {
            return [
                'temporary_access_expires_at' => now()->addDays(30),
            ];
        });
    }

    public function withRole($roleSlug)
    {
        return $this->state(function (array $attributes) use ($roleSlug) {
            $role = \App\Modules\Auth\Models\Role::where('slug', $roleSlug)->first();
            
            if (!$role) {
                // Si el rol no existe, crear uno con el slug solicitado
                $role = \App\Modules\Auth\Models\Role::create([
                    'name' => ucfirst($roleSlug),
                    'slug' => $roleSlug,
                    'description' => 'Rol creado para testing'
                ]);
            }
            
            return [
                'role_id' => $role->id,
            ];
        });
    }
}
