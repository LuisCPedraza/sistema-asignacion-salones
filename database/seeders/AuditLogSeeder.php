<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un log de prueba
        $user = User::first();
        
        if ($user) {
            AuditLog::log(
                User::class,
                $user->id,
                'create',
                null,
                ['name' => $user->name, 'email' => $user->email],
                "Test: Usuario creado en seeder"
            );

            $this->command->info('✅ Registro de auditoría de prueba creado');
        } else {
            $this->command->warn('⚠️ No hay usuarios en la base de datos');
        }
    }
}
