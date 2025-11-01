<?php

namespace Database\Factories;

use App\Models\Salon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalonFactory extends Factory
{
    protected $model = Salon::class;

    public function definition(): array
    {
        return [
            'codigo' => fake()->unique()->regexify('S[0-9]{3}-[A-Z]{2}'),  // E.g., "S101-A1"
            'capacidad' => fake()->numberBetween(20, 100),  // Capacidad 20-100 >0
            'ubicacion' => fake()->streetAddress(),  // Ubicación random
            'activo' => fake()->boolean(80),  // 80% activo
        ];
    }
}
