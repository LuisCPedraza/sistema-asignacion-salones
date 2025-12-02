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

        // Limpiar disponibilidades existentes primero
        TeacherAvailability::query()->delete();
        ClassroomAvailability::query()->delete();

        // Crear disponibilidades para profesores - USAR SOLO 'day'
        $teachers = Teacher::all();
        $teacherDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        $teacherAvailabilityCount = 0;
        foreach ($teachers as $teacher) {
            foreach ($teacherDays as $day) {
                TeacherAvailability::create([
                    'teacher_id' => $teacher->id,
                    'day' => $day, // SOLO usar 'day', no 'day_of_week'
                    'start_time' => '08:00:00',
                    'end_time' => '12:00:00',
                    'is_available' => true,
                ]);
                
                TeacherAvailability::create([
                    'teacher_id' => $teacher->id,
                    'day' => $day, // SOLO usar 'day', no 'day_of_week'
                    'start_time' => '14:00:00',
                    'end_time' => '18:00:00',
                    'is_available' => true,
                ]);
                $teacherAvailabilityCount += 2;
            }
        }

        $this->command->info("✅ {$teachers->count()} profesores con {$teacherAvailabilityCount} disponibilidades creadas.");

        // Crear disponibilidades para salones - USAR SOLO 'day'
        $classrooms = Classroom::active()->get();
        $classroomDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        $classroomAvailabilityCount = 0;
        foreach ($classrooms as $classroom) {
            foreach ($classroomDays as $day) {
                ClassroomAvailability::create([
                    'classroom_id' => $classroom->id,
                    'day' => $day, // SOLO usar 'day', no 'day_of_week'
                    'start_time' => '07:00:00',
                    'end_time' => '21:00:00',
                    'is_available' => true,
                ]);
                $classroomAvailabilityCount++;
            }
        }

        $this->command->info("✅ {$classrooms->count()} salones con {$classroomAvailabilityCount} disponibilidades creadas.");
        
        $this->command->info('🎉 Disponibilidades creadas exitosamente!');
    }
}