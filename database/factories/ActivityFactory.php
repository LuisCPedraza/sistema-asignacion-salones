<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Modules\Asignacion\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'due_date' => Carbon::today()->addWeek(),
            'max_score' => 100,
            'created_by' => null,
        ];
    }
}
