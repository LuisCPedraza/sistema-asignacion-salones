<?php

namespace Database\Factories;

use App\Models\PropuestaAsignacion;
use App\Models\Asignacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropuestaAsignacionFactory extends Factory
{
    protected $model = PropuestaAsignacion::class;

    public function definition(): array
    {
        return [
            'asignacion_id' => Asignacion::factory(),  // Asignación random (FK)
            'score' => fake()->randomFloat(2, 0, 100),  // Score 0-100, 2 decimals
            'conflictos' => [  // JSON random conflictos
                'conflicto' => fake()->randomElements(['horario', 'salon', 'profesor'], fake()->numberBetween(0, 2)),
            ],
            'orden' => fake()->numberBetween(1, 5),  // Orden 1-5
            'activo' => fake()->boolean(90),  // 90% activo
        ];
    }
}
