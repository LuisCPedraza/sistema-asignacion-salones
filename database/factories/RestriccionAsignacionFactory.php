<?php

namespace Database\Factories;

use App\Models\RestriccionAsignacion;
use App\Models\Salon;
use App\Models\Profesor;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestriccionAsignacionFactory extends Factory
{
    protected $model = RestriccionAsignacion::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['salon', 'profesor']);
        $recurso = $type === 'salon' ? Salon::factory() : Profesor::factory();
        return [
            'recurso_type' => $type,  // Random 'salon' or 'profesor'
            'recurso_id' => $recurso,  // ID from random Salon or Profesor
            'tipo_restriccion' => fake()->randomElement(['horario', 'capacidad', 'especial']),  // Random enum
            'valor' => [  // JSON random valor
                'dias' => fake()->randomElements(['lun', 'mar', 'mie', 'jue', 'vie'], fake()->numberBetween(1, 5)),
                'max_cap' => fake()->numberBetween(10, 50),
                'especial' => fake()->randomElement(['proyector requerido', 'sin aire acondicionado']),
            ],
            'activo' => fake()->boolean(85),  // 85% activo
        ];
    }
}
