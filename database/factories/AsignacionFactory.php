<?php

namespace Database\Factories;

use App\Models\Asignacion;
use App\Models\Grupo;
use App\Models\Salon;
use App\Models\Profesor;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsignacionFactory extends Factory
{
    protected $model = Asignacion::class;

    public function definition(): array
    {
        return [
            'grupo_id' => Grupo::factory(),  // Grupo random (FK)
            'salon_id' => Salon::factory(),  // Salon random (FK)
            'profesor_id' => Profesor::factory(),  // Profesor random (FK)
            'fecha' => fake()->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'),  // Fecha random
            'hora' => fake()->time('H:i'),  // Hora random
            'estado' => fake()->randomElement(['propuesta', 'confirmada', 'cancelada']),  // Estado random enum
            'score' => fake()->randomFloat(2, 0, 100),  // Score 0-100, 2 decimals
            'conflictos' => [  // JSON random conflictos
                'conflicto' => fake()->randomElements(['horario', 'salon', 'profesor'], fake()->numberBetween(0, 2)),
            ],
            'activo' => fake()->boolean(80),  // 80% activo
        ];
    }
}
