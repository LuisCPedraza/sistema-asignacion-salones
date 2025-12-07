<?php

namespace Database\Factories\Modules\GestionAcademica;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\GestionAcademica\Models\StudentGroup;

class StudentGroupFactory extends Factory
{
    protected $model = StudentGroup::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'level' => $this->faker->randomElement(['Bachillerato', 'Universitario', 'Posgrado']),
            'student_count' => $this->faker->numberBetween(10, 50),
            'special_features' => $this->faker->sentence(),
            'is_active' => true,
            'academic_period_id' => null,
        ];
    }
}