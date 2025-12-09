<?php

namespace Database\Factories\Modules\Asignacion\Models;

use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition(): array
    {
        return [
            'student_group_id' => StudentGroup::factory(),
            'classroom_id' => Classroom::factory(),
            'teacher_id' => Teacher::factory(),
            'day' => $this->faker->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
            'is_confirmed' => $this->faker->boolean(80),
            'notes' => $this->faker->sentence(),
        ];
    }
}
