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
            'codigo' => fake()->unique()->regexify('[A-Z]{2}[0-9]{3}'),  // E.g., "A101"
            'capacidad' => fake()->numberBetween(20, 100),  // Capacidad 20-100
            'ubicacion' => fake()->streetAddress(),  // E.g., "Calle Falsa 123"
            'activo' => true,  // Default activo
        ];
    }
}

