<?php

namespace Database\Factories\Modules\Asignacion;

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
            'group_id' => StudentGroup::factory(),
            'classroom_id' => Classroom::factory(),
            'teacher_id' => Teacher::factory(),
            'subject_id' => $this->faker->numberBetween(1, 20),
            'day_of_week' => $this->faker->randomElement(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']),
            'start_time' => $this->faker->time('H:i:s'),
            'end_time' => $this->faker->time('H:i:s'),
            'score' => $this->faker->randomFloat(2, 0.5, 1.0),
            'conflicts_detected' => false,
            'notes' => $this->faker->sentence(),
        ];
    }
}
