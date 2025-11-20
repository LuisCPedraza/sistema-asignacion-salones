<?php

namespace Database\Factories;

use App\Models\Profesor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorFactory extends Factory
{
    protected $model = Profesor::class;

    public function definition(): array
    {
        return [
            'usuario_id' => User::factory(),  // Crea automáticamente un usuario relacionado

            // randomElements() devuelve un ARRAY → usamos implode() de PHP
            'especialidades' => implode(', ', fake()->randomElements(
                ['Matemáticas', 'Física', 'Biología', 'Historia'],
                2
            )),

            'activo' => fake()->boolean(90), // 90% activo
        ];
    }
}

