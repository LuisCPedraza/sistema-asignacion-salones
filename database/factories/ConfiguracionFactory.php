<?php

namespace Database\Factories;

use App\Models\Configuracion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfiguracionFactory extends Factory
{
    protected $model = Configuracion::class;

    public function definition(): array
    {
        return [
            'key' => 'config_' . fake()->unique()->slug(),  // Key unique (e.g., "config_horarios_default")
            'value' => [  // JSON array de parámetros random
                'horarios' => fake()->randomElements(['Lun-Vie 8-18', 'Lun-Vie 9-19', 'Lun-Sab 8-14'], 1),
                'max_asignaciones' => fake()->numberBetween(5, 15),
                'roles_permitidos' => ['admin', 'coordinador'],
            ],
            'activo' => fake()->boolean(95),  // 95% activo
        ];
    }
}
