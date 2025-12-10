<?php

namespace Database\Factories\Models;

use App\Models\Student;
use App\Modules\GestionAcademica\Models\StudentGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'codigo' => 'EST-' . fake()->unique()->numerify('####-###'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName() . ' ' . fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->optional()->phoneNumber(),
            'group_id' => StudentGroup::factory(),
            'estado' => $this->faker->randomElement(['activo', 'activo', 'activo', 'inactivo', 'retirado']), // Mayor probabilidad de activo
            'observaciones' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Estado activo
     */
    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'activo',
        ]);
    }

    /**
     * Estado inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }

    /**
     * Estado retirado
     */
    public function retirado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'retirado',
        ]);
    }
}
