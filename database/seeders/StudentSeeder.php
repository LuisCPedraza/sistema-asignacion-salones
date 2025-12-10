<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Modules\GestionAcademica\Models\StudentGroup;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        
        $groups = StudentGroup::all();

        foreach ($groups as $group) {
            for ($i = 0; $i < 5; $i++) {
                Student::factory()->create([
                    'group_id' => $group->id,
                    'nombre' => $faker->firstName(),
                    'apellido' => $faker->lastName(),
                    'codigo' => 'EST-' . $group->id . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'email' => $faker->unique()->safeEmail(),
                    'estado' => 'activo',
                ]);
            }
        }

        $this->command->info('✓ ' . Student::count() . ' estudiantes creados correctamente');
    }
}
