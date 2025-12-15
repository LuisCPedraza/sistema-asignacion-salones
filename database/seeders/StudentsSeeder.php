<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Modules\Asignacion\Models\Assignment;

class StudentsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎯 Creando estudiantes para grupos de assignments...');

        $assignments = Assignment::with(['group', 'subject', 'teacher'])->get();
        $created = 0;

        foreach ($assignments as $assignment) {
            $group = $assignment->group;
            if (! $group) {
                continue;
            }

            // Contar estudiantes existentes en el grupo
            $existingCount = method_exists($group, 'students') ? $group->students()->count() : 0;

            // Queremos al menos entre 5 y 15 estudiantes
            $targetMin = 5;
            $targetMax = 15;
            $target = max($targetMin, $existingCount);
            if ($target < $targetMin) {
                $target = $targetMin;
            } elseif ($target > $targetMax) {
                $target = $targetMax; // si ya hay más no añadimos
            }

            // Si ya tiene >= 5, no crear más; si tiene menos, completar hasta 5
            if ($existingCount >= $targetMin) {
                continue;
            }

            $toCreate = $targetMin - $existingCount;

            for ($i = 0; $i < $toCreate; $i++) {
                $nombre = fake()->firstName();
                $apellido = fake()->lastName();
                $codigo = strtoupper(Str::random(8));
                $email = strtolower($nombre.'.'.$apellido.'.'.$codigo).'@example.edu';

                Student::create([
                    'codigo' => $codigo,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'email' => $email,
                    'telefono' => fake()->optional()->phoneNumber(),
                    'group_id' => $group->id,
                    'estado' => 'activo',
                    'observaciones' => 'Generado por StudentsSeeder',
                ]);
                $created++;
            }
        }

        $this->command->info("✅ Estudiantes creados: {$created}");
    }
}
