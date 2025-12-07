<?php

namespace Database\Factories\Modules\Auth;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Auth\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence,
            'is_active' => true,
        ];
    }

    // Estados para cada tipo de rol
    public function administrador()
    {
        return $this->state([
            'name' => 'Administrador',
            'slug' => Role::ADMINISTRADOR,
            'description' => 'Administrador del sistema',
        ]);
    }

    public function coordinador()
    {
        return $this->state([
            'name' => 'Coordinador',
            'slug' => Role::COORDINADOR,
            'description' => 'Coordinador académico',
        ]);
    }

    public function profesor()
    {
        return $this->state([
            'name' => 'Profesor',
            'slug' => Role::PROFESOR,
            'description' => 'Profesor',
        ]);
    }

    public function secretariaAdministrativa()
    {
        return $this->state([
            'name' => 'Secretaria Administrativa',
            'slug' => Role::SECRETARIA_ADMINISTRATIVA,
            'description' => 'Secretaria administrativa',
        ]);
    }
}