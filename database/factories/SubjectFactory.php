<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'code' => 'SUB-'.$this->faker->unique()->numerify('###'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'specialty' => $this->faker->randomElement(['Informatica', 'Matematica', 'Fisica']),
            'credit_hours' => $this->faker->numberBetween(2, 5),
            'lecture_hours' => $this->faker->numberBetween(1, 3),
            'lab_hours' => $this->faker->numberBetween(0, 2),
            'semester_level' => $this->faker->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
