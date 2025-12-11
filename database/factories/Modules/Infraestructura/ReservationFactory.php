<?php

namespace Database\Factories\Modules\Infraestructura;

use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 day', '+2 weeks');
        $end = (clone $start)->modify('+2 hours');

        return [
            'classroom_id' => Classroom::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(8),
            'requester_name' => $this->faker->name(),
            'requester_email' => $this->faker->safeEmail(),
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'pendiente',
            'notes' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => 'aprobada']);
    }

    public function rejected(): static
    {
        return $this->state(['status' => 'rechazada']);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => 'cancelada']);
    }
}
