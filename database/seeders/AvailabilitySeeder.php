<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\ClassroomAvailability;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎯 Creando disponibilidades para profesores y salones...');

        // Homogeneizar: borrar disponibilidades existentes y sembrar estándar
        TeacherAvailability::query()->delete();
        ClassroomAvailability::query()->delete();

        // Crear disponibilidades para profesores - USAR SOLO 'day'
        $teachers = Teacher::all();
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $teacherAvailabilityCount = 0;
        foreach ($teachers as $teacher) {
            // Lunes a viernes: 08:00 a 22:00
            foreach ($weekdays as $day) {
                $exists = TeacherAvailability::where('teacher_id', $teacher->id)
                    ->where('day', $day)
                    ->where('start_time', '08:00:00')
                    ->where('end_time', '22:00:00')
                    ->exists();
                if (! $exists) {
                    TeacherAvailability::create([
                        'teacher_id' => $teacher->id,
                        'day' => $day,
                        'start_time' => '08:00:00',
                        'end_time' => '22:00:00',
                        'is_available' => true,
                        'notes' => 'Disponibilidad estándar (L–V 08–22)'
                    ]);
                    $teacherAvailabilityCount++;
                }
            }

            // Sábado: 08:00 a 14:00
            $existsSat = TeacherAvailability::where('teacher_id', $teacher->id)
                ->where('day', 'saturday')
                ->where('start_time', '08:00:00')
                ->where('end_time', '14:00:00')
                ->exists();
            if (! $existsSat) {
                TeacherAvailability::create([
                    'teacher_id' => $teacher->id,
                    'day' => 'saturday',
                    'start_time' => '08:00:00',
                    'end_time' => '14:00:00',
                    'is_available' => true,
                    'notes' => 'Disponibilidad estándar (Sáb 08–14)'
                ]);
                $teacherAvailabilityCount++;
            }
        }

        $this->command->info("✅ {$teachers->count()} profesores con {$teacherAvailabilityCount} disponibilidades creadas.");

        // Crear disponibilidades para salones - USAR SOLO 'day'
        $classrooms = Classroom::active()->get();
        $classroomAvailabilityCount = 0;
        foreach ($classrooms as $classroom) {
            // Lunes a viernes: 08:00 a 22:00
            foreach ($weekdays as $day) {
                $existsRoom = ClassroomAvailability::where('classroom_id', $classroom->id)
                    ->where('day', $day)
                    ->where('start_time', '08:00:00')
                    ->where('end_time', '22:00:00')
                    ->exists();
                if (! $existsRoom) {
                    ClassroomAvailability::create([
                        'classroom_id' => $classroom->id,
                        'day' => $day,
                        'start_time' => '08:00:00',
                        'end_time' => '22:00:00',
                        'is_available' => true,
                        'availability_type' => 'regular',
                        'notes' => 'Disponibilidad estándar (L–V 08–22)'
                    ]);
                    $classroomAvailabilityCount++;
                }
            }
            // Sábado: 08:00 a 14:00
            $existsRoomSat = ClassroomAvailability::where('classroom_id', $classroom->id)
                ->where('day', 'saturday')
                ->where('start_time', '08:00:00')
                ->where('end_time', '14:00:00')
                ->exists();
            if (! $existsRoomSat) {
                ClassroomAvailability::create([
                    'classroom_id' => $classroom->id,
                    'day' => 'saturday',
                    'start_time' => '08:00:00',
                    'end_time' => '14:00:00',
                    'is_available' => true,
                    'availability_type' => 'regular',
                    'notes' => 'Disponibilidad estándar (Sáb 08–14)'
                ]);
                $classroomAvailabilityCount++;
            }
        }

        $this->command->info("✅ {$classrooms->count()} salones con {$classroomAvailabilityCount} disponibilidades creadas.");
        
        $this->command->info('🎉 Disponibilidades creadas exitosamente!');
    }
}