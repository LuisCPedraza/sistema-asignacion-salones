<?php

namespace Database\Factories;

use App\Models\LogVisualizacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogVisualizacionFactory extends Factory
{
    protected $model = LogVisualizacion::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['rol' => fake()->randomElement(['admin', 'coordinador'])]),  // User random rol admin/coordinador (FK)
            'filtro' => [  // JSON random filtros
                'grupo' => fake()->randomElement(['G101-MAT', 'G102-FIS', 'G103-BIO']),
                'fecha' => fake()->date(),
                'estado' => fake()->randomElement(['propuesta', 'confirmada']),
            ],
            'fecha' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),  // Fecha random
            'activo' => fake()->boolean(95),  // 95% activo
        ];
    }
}
