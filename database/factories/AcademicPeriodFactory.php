<?php

namespace Database\Factories;

use App\Models\AcademicPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicPeriodFactory extends Factory
{
    protected $model = AcademicPeriod::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' ' . $this->faker->year(),
            'start_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+2 years'),
            'is_active' => true,
        ];
    }
}
