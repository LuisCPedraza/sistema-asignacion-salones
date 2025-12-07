<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\GestionAcademica\Models\Teacher;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $specialties = [
            'Matemáticas', 'Física', 'Química', 'Biología', 'Programación',
            'Bases de Datos', 'Redes', 'Inteligencia Artificial', 'Ingeniería de Software',
            'Sistemas Operativos', 'Estadística', 'Investigación de Operaciones'
        ];

        $academic_degrees = [
            'Licenciatura', 'Ingeniería', 'Maestría', 'Doctorado', 'Especialización'
        ];

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'specialty' => $this->faker->randomElement($specialties),
            'specialties' => $this->faker->randomElements($specialties, $this->faker->numberBetween(1, 3)),
            'curriculum' => $this->faker->paragraphs(3, true),
            'years_experience' => $this->faker->numberBetween(1, 30),
            'academic_degree' => $this->faker->randomElement($academic_degrees),
            'is_active' => $this->faker->boolean(80),
            'availability_notes' => $this->faker->optional()->sentence(),
            'weekly_availability' => null, // Simplificar para tests
            'special_assignments' => $this->faker->optional()->sentence(),
            'user_id' => null,
        ];
    }
}