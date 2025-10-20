<?php

namespace Database\Factories;

use App\Models\Grupo;
use Illuminate\Database\Eloquent\Factories\Factory;

class GrupoFactory extends Factory
{
    protected $model = Grupo::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->regexify('G[0-9]{3}-[A-Z]{2}'),  // E.g., "G101-MAT"
            'nivel' => fake()->randomElement(['basico', 'intermedio', 'avanzado']),  // Nivel random
            'num_estudiantes' => fake()->numberBetween(20, 100),  // Num estudiantes 20-100 >0
            'activo' => true,  // Default activo
        ];
    }
}
