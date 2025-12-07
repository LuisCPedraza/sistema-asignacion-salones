<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Infraestructura\Models\ClassroomAvailability;
use App\Modules\Infraestructura\Models\Classroom;

class ClassroomAvailabilityFactory extends Factory
{
    protected $model = ClassroomAvailability::class;

    public function definition()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        return [
            'classroom_id' => Classroom::factory(),
            'day' => $this->faker->randomElement($days),
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'is_available' => true,
            'availability_type' => 'regular',
            'notes' => $this->faker->sentence(),
        ];
    }
}