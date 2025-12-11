<?php

namespace Database\Factories\Modules\Infraestructura;

use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Maintenance;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceFactory extends Factory
{
    protected $model = Maintenance::class;

    public function definition(): array
    {
        return [
            'classroom_id' => Classroom::factory(),
            'type' => $this->faker->randomElement(['preventivo', 'correctivo']),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => 'pendiente',
            'scheduled_date' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'responsible' => $this->faker->name(),
            'cost' => $this->faker->randomFloat(2, 50, 5000),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pendiente']);
    }

    public function inProgress(): static
    {
        return $this->state([
            'status' => 'en_progreso',
            'start_date' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => 'completado',
            'start_date' => now()->subDays(5),
            'end_date' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => 'cancelado']);
    }

    public function preventive(): static
    {
        return $this->state(['type' => 'preventivo']);
    }

    public function corrective(): static
    {
        return $this->state(['type' => 'correctivo']);
    }
}
