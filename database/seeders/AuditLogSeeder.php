<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;
use App\Modules\Asignacion\Models\Assignment;
use App\Models\Student;
use App\Modules\GestionAcademica\Models\Teacher;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Generando registros de auditoría...');

        // Obtener usuarios del sistema
        $users = User::limit(5)->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('⚠️ No hay usuarios en la base de datos');
            return;
        }

        $adminUser = $users->first();
        $createdCount = 0;

        // 1. Registros de creación de usuarios
        foreach ($users->take(3) as $user) {
            AuditLog::create([
                'user_id' => $adminUser->id,
                'model' => 'User',
                'model_id' => $user->id,
                'action' => 'create',
                'old_values' => null,
                'new_values' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => 'usuario'
                ],
                'description' => "Usuario {$user->name} registrado en el sistema",
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(rand(5, 15)),
            ]);
            $createdCount++;
        }

        // 2. Registros de asignaciones (si existen)
        $assignments = Assignment::with('teacher', 'subject', 'group')->limit(10)->get();
        foreach ($assignments->take(5) as $assignment) {
            $subjectName = $assignment->subject->name ?? 'N/A';
            $teacherFirstName = $assignment->teacher->first_name ?? '';
            $teacherLastName = $assignment->teacher->last_name ?? '';
            
            AuditLog::create([
                'user_id' => $adminUser->id,
                'model' => 'Assignment',
                'model_id' => $assignment->id,
                'action' => 'create',
                'old_values' => null,
                'new_values' => [
                    'teacher_id' => $assignment->teacher_id,
                    'subject_id' => $assignment->subject_id,
                    'group_id' => $assignment->group_id,
                    'day' => $assignment->day,
                ],
                'description' => "Asignación de materia {$subjectName} al profesor {$teacherFirstName} {$teacherLastName}",
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(rand(3, 10)),
            ]);
            $createdCount++;
        }

        // 3. Registros de actualización de asignaciones
        foreach ($assignments->skip(5)->take(3) as $assignment) {
            AuditLog::create([
                'user_id' => $adminUser->id,
                'model' => 'Assignment',
                'model_id' => $assignment->id,
                'action' => 'update',
                'old_values' => [
                    'classroom_id' => $assignment->classroom_id,
                    'start_time' => '08:00:00',
                ],
                'new_values' => [
                    'classroom_id' => $assignment->classroom_id,
                    'start_time' => $assignment->start_time,
                ],
                'description' => "Actualización de horario de asignación #{$assignment->id}",
                'ip_address' => '192.168.1.' . rand(100, 200),
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
            ]);
            $createdCount++;
        }

        // 4. Registros de estudiantes (si existen)
        $students = Student::limit(5)->get();
        foreach ($students as $student) {
            AuditLog::create([
                'user_id' => $adminUser->id,
                'model' => 'Student',
                'model_id' => $student->id,
                'action' => 'create',
                'old_values' => null,
                'new_values' => [
                    'codigo' => $student->codigo,
                    'nombre' => $student->nombre,
                    'apellido' => $student->apellido,
                    'email' => $student->email,
                ],
                'description' => "Estudiante {$student->nombre} {$student->apellido} registrado",
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => Carbon::now()->subDays(rand(7, 20)),
            ]);
            $createdCount++;
        }

        // 5. Registros de profesores (si existen)
        $teachers = Teacher::limit(5)->get();
        foreach ($teachers->take(3) as $teacher) {
            AuditLog::create([
                'user_id' => $adminUser->id,
                'model' => 'Teacher',
                'model_id' => $teacher->id,
                'action' => 'create',
                'old_values' => null,
                'new_values' => [
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'email' => $teacher->email,
                    'specialty' => $teacher->specialty,
                ],
                'description' => "Profesor {$teacher->first_name} {$teacher->last_name} agregado al sistema",
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/121.0',
                'created_at' => Carbon::now()->subDays(rand(10, 25)),
            ]);
            $createdCount++;
        }

        // 6. Registro de exportación
        AuditLog::create([
            'user_id' => $adminUser->id,
            'model' => 'Report',
            'model_id' => null,
            'action' => 'export',
            'old_values' => null,
            'new_values' => [
                'report_type' => 'utilization',
                'format' => 'pdf',
            ],
            'description' => "Exportación de reporte de utilización a PDF",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
            'created_at' => Carbon::now()->subHours(rand(1, 24)),
        ]);
        $createdCount++;

        // 7. Registros de eliminación (simulados)
        if ($assignments->count() > 0) {
            $assignment = $assignments->last();
            AuditLog::create([
                'user_id' => $adminUser->id,
                'model' => 'Assignment',
                'model_id' => $assignment->id,
                'action' => 'delete',
                'old_values' => [
                    'teacher_id' => $assignment->teacher_id,
                    'subject_id' => $assignment->subject_id,
                ],
                'new_values' => null,
                'description' => "Asignación #{$assignment->id} eliminada del sistema",
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Safari/17.0',
                'created_at' => Carbon::now()->subDays(1),
            ]);
            $createdCount++;
        }

        $this->command->info("✅ {$createdCount} registros de auditoría creados exitosamente");
    }
}
