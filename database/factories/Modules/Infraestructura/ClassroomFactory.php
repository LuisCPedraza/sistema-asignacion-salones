<?php

namespace Database\Factories\Modules\Infraestructura;

use App\Modules\Infraestructura\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition(): array
    {
        return [
            'name' => 'Sala ' . $this->faker->bothify('###'),
            'code' => strtoupper($this->faker->unique()->bothify('SA-##')),
            'capacity' => $this->faker->numberBetween(20, 50),
            'resources' => $this->faker->words(3, true),
            'location' => $this->faker->word(),
            'special_features' => $this->faker->sentence(),
            'is_active' => true,
            'type' => $this->faker->randomElement(['aula', 'laboratorio', 'taller']),
            'floor' => $this->faker->numberBetween(1, 5),
            'wing' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
        ];
    }
}
