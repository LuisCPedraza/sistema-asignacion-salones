<?php

namespace Database\Factories;

use App\Models\ActivityGrade;
use App\Models\Student;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ActivityGradeFactory extends Factory
{
    protected $model = ActivityGrade::class;

    public function definition(): array
    {
        return [
            'activity_id' => Activity::factory(),
            'student_id' => Student::factory(),
            'score' => $this->faker->randomFloat(1, 0, 100),
            'feedback' => $this->faker->optional()->sentence(),
            'graded_at' => Carbon::now(),
            'graded_by' => null,
        ];
    }
}
