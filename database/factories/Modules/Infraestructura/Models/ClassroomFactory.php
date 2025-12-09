<?php

namespace Database\Factories\Modules\Infraestructura\Models;

use App\Modules\Infraestructura\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition(): array
    {
        return [
            'name' => 'Aula '.$this->faker->numerify('###'),
            'code' => 'A'.$this->faker->unique()->numerify('###'),
            'capacity' => $this->faker->numberBetween(20, 60),
            'resources' => ['proyector'],
            'location' => $this->faker->randomElement(['Edificio A', 'Edificio B']),
            'special_features' => null,
            'is_active' => true,
            'restrictions' => null,
            'type' => 'aula',
            'floor' => $this->faker->numberBetween(1, 3),
            'wing' => $this->faker->randomElement(['Norte', 'Sur']),
            'building_id' => null,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
