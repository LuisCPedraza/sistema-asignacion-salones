<?php

namespace Database\Factories;

use App\Models\Semester;
use App\Models\Career;
use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    protected $model = Semester::class;

    public function definition(): array
    {
        return [
            'career_id' => Career::factory(),
            'number' => $this->faker->numberBetween(1, 8),
            'description' => 'Semestre ' . $this->faker->numberBetween(1, 8),
            'is_active' => true,
        ];
    }
}
