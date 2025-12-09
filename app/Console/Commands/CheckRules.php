<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Asignacion\Models\AssignmentRule;

class CheckRules extends Command
{
    protected $signature = 'check:rules';

    protected $description = 'Verifica las reglas de asignación activas';

    public function handle()
    {
        $rules = AssignmentRule::where('is_active', true)->get();
        
        $this->info("Reglas activas: " . $rules->count());
        
        if ($rules->isEmpty()) {
            $this->error("❌ NO HAY REGLAS ACTIVAS - Este es el problema!");
            $this->info("Creando reglas por defecto...");
            $this->createDefaultRules();
        } else {
            foreach ($rules as $rule) {
                $this->line("✓ {$rule->name} (peso: {$rule->weight})");
            }
        }
    }

    private function createDefaultRules()
    {
        $defaultRules = [
            [
                'name' => 'Capacidad Óptima',
                'slug' => 'capacidad_optima',
                'description' => 'Asignar salones con capacidad adecuada al número de estudiantes',
                'weight' => 1.5,
                'is_active' => true,
            ],
            [
                'name' => 'Equipamiento Necesario',
                'slug' => 'equipamiento_necesario',
                'description' => 'Verificar que el salón tenga los recursos necesarios',
                'weight' => 1.2,
                'is_active' => true,
            ],
            [
                'name' => 'Minimizar Cambios de Salón',
                'slug' => 'minimizar_cambios_salon',
                'description' => 'Reducir cambios de salón para el mismo grupo',
                'weight' => 0.8,
                'is_active' => true,
            ],
            [
                'name' => 'Preferencias Horarias',
                'slug' => 'preferencias_horarias',
                'description' => 'Respetar preferencias de horarios',
                'weight' => 0.6,
                'is_active' => true,
            ],
        ];

        foreach ($defaultRules as $rule) {
            AssignmentRule::create($rule);
            $this->info("✓ Creada: {$rule['name']}");
        }

        $this->info("\n✅ Reglas creadas exitosamente!");
    }
}
