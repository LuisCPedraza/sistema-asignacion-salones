<?php

namespace App\Console\Commands;

use App\Modules\GestionAcademica\Models\StudentGroup;
use Illuminate\Console\Command;

class FixStudentCounts extends Command
{
    protected $signature = 'fix:student-counts';
    protected $description = 'Asignar cantidad aleatoria de estudiantes a grupos que tengan 0';

    public function handle()
    {
        $grupos = StudentGroup::all();
        
        $this->info("Total de grupos: " . $grupos->count());
        
        foreach ($grupos as $grupo) {
            $this->info("{$grupo->name} - Estudiantes actuales: {$grupo->student_count}");
            
            if ($grupo->student_count == 0 || $grupo->student_count === null) {
                // Asignar cantidad aleatoria según el nivel
                $cantidad = match($grupo->level) {
                    'Posgrado' => rand(8, 15),
                    'Diplomado' => rand(10, 20),
                    'Curso Corto' => rand(5, 12),
                    default => rand(15, 35) // Universitario
                };
                
                $grupo->student_count = $cantidad;
                $grupo->save();
                
                $this->info("✅ Actualizado: {$grupo->name} → {$cantidad} estudiantes");
            }
        }
        
        $this->info("\n✅ Proceso completado");
        return 0;
    }
}
