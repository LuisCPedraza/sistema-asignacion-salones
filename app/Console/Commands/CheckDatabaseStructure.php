<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CheckDatabaseStructure extends Command
{
    protected $signature = 'db:check-structure';
    protected $description = 'Verificar la estructura de las tablas de la base de datos';

    public function handle()
    {
        $this->info('🔍 VERIFICANDO ESTRUCTURA DE LA BASE DE DATOS');

        $tables = [
            'student_groups',
            'teacher_availabilities', 
            'classroom_availabilities',
            'teachers',
            'classrooms'
        ];

        foreach ($tables as $table) {
            $this->checkTable($table);
        }

        return Command::SUCCESS;
    }

    private function checkTable($tableName)
    {
        $this->info("\n📊 TABLA: {$tableName}");

        if (!Schema::hasTable($tableName)) {
            $this->error("   ❌ La tabla no existe");
            return;
        }

        $columns = Schema::getColumnListing($tableName);
        
        $this->line("   - Columnas: " . implode(', ', $columns));
        
        // Verificar datos
        $count = \DB::table($tableName)->count();
        $this->line("   - Registros: {$count}");

        if ($count > 0) {
            $sample = \DB::table($tableName)->first();
            $this->line("   - Ejemplo: " . json_encode($sample));
        }
    }
}
