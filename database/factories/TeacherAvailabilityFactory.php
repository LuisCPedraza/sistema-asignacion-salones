<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\GestionAcademica\Models\Teacher;

class TeacherAvailabilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeacherAvailability::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $startTimes = ['08:00', '10:00', '14:00', '16:00', '18:00'];
        
        $startTime = $this->faker->randomElement($startTimes);
        $endTime = date('H:i', strtotime($startTime) + 7200); // +2 horas

        return [
            'teacher_id' => Teacher::factory(),
            'day' => $this->faker->randomElement($days),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_available' => $this->faker->boolean(80),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}