<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;

class TestGenerateAssignments extends Command
{
    protected $signature = 'test:generate-assignments';
    protected $description = 'Test el algoritmo de asignación automática';

    public function handle()
    {
        $before = Assignment::count();
        $this->info("📊 Asignaciones antes: {$before}");

        $algorithm = new AssignmentAlgorithm();
        $asignaciones = $algorithm->generateAssignments();

        $after = Assignment::count();
        $updated = count($asignaciones);

        $this->info("🔄 Asignaciones reorganizadas: {$updated}");
        $this->info("📊 Asignaciones después: {$after}");
        
        if ($updated > 0) {
            $this->info("✅ Algoritmo ejecutado correctamente - Se reacomodaron {$updated} asignaciones");
        } else {
            $this->warn("⚠️  El algoritmo no generó cambios - Probablemente algo está mal");
        }

        return 0;
    }
}
