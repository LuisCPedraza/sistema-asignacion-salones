<?php

namespace Database\Factories;

use App\Models\HistorialAsignacion;
use App\Models\Asignacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistorialAsignacionFactory extends Factory
{
    protected $model = HistorialAsignacion::class;

    public function definition(): array
    {
        return [
            'asignacion_id' => Asignacion::factory(),  // Asignación random (FK)
            'user_id' => User::factory()->create(['rol' => fake()->randomElement(['admin', 'coordinador'])]),  // Usuario random rol admin/coordinador
            'accion' => fake()->randomElement(['create', 'update', 'delete']),  // Acción random enum
            'cambios' => [  // JSON random cambios
                'old_estado' => fake()->randomElement(['propuesta', 'confirmada']),
                'new_estado' => fake()->randomElement(['confirmada', 'cancelada']),
                'old_score' => fake()->numberBetween(0, 100),
                'new_score' => fake()->numberBetween(0, 100),
            ],
            'fecha' => fake()->dateTimeBetween('-1 year', 'now'),  // Fecha random
            'activo' => fake()->boolean(95),  // 95% activo
        ];
    }
}