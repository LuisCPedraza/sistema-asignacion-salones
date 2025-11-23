<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Auth\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }

    public function administrator()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Administrador',
                'slug' => Role::ADMINISTRADOR,
                'description' => 'Acceso completo al sistema',
            ];
        });
    }

    public function professor()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Profesor',
                'slug' => Role::PROFESOR,
                'description' => 'Acceso a horarios personales',
            ];
        });
    }

    public function coordinador()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Coordinador',
                'slug' => Role::COORDINADOR,
                'description' => 'Gestión académica y asignaciones',
            ];
        });
    }
}
