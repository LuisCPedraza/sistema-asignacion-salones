<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Asignacion\Models\AssignmentRule;

class AssignmentRuleSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('📋 Creando reglas de asignación...');

        $rules = [
            [
                'parameter' => 'capacity',
                'weight' => 30,
                'is_active' => true,
                'description' => 'Capacidad del salón'
            ],
            [
                'parameter' => 'teacher_availability',
                'weight' => 25, 
                'is_active' => true,
                'description' => 'Disponibilidad del profesor'
            ],
            [
                'parameter' => 'classroom_availability',
                'weight' => 25,
                'is_active' => true,
                'description' => 'Disponibilidad del salón'
            ],
            [
                'parameter' => 'resources',
                'weight' => 10,
                'is_active' => true,
                'description' => 'Recursos requeridos'
            ],
            [
                'parameter' => 'proximity',
                'weight' => 10,
                'is_active' => true,
                'description' => 'Proximidad'
            ]
        ];

        foreach ($rules as $rule) {
            AssignmentRule::firstOrCreate(
                ['parameter' => $rule['parameter']],
                $rule
            );
            $this->command->info("   ✅ Regla: {$rule['parameter']} (peso: {$rule['weight']})");
        }

        $this->command->info('🎯 Reglas de asignación creadas exitosamente');
    }
}
