<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SyncToSupabase extends Command
{
    protected $signature = 'sync:supabase';
    protected $description = 'Sincroniza datos de SQLite a Supabase PostgreSQL';

    public function handle()
    {
        $this->info('Iniciando sincronizacion de datos a Supabase...');
        
        try {
            $this->info('Verificando conexion a Supabase...');
            $this->checkSupabaseConnection();

            $this->info('Conexion a Supabase establecida');

            $tables = [
                'roles',
                'users',
                'academic_periods',
                'careers',
                'semesters',
                'student_groups',
                'teachers',
                'subjects',
                'time_slots',
                'buildings',
                'classrooms',
                'classroom_availabilities',
                'teacher_availabilities',
                'assignment_rules',
                'assignments',
                'course_schedules',
            ];

            foreach ($tables as $table) {
                $this->syncTableDirect($table);
            }

            $this->info('Sincronizacion completada con exito!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }

    private function syncTableDirect($table)
    {
        try {
            $this->line("Procesando tabla: $table");
            
            $localData = DB::table($table)->get()->toArray();
            $count = count($localData);

            if ($count === 0) {
                $this->line("[SKIP] $table: sin datos");
                return;
            }

            $data = array_map(function ($row) {
                return (array) $row;
            }, $localData);

            // Usar conexion Supabase
            $supabaseConn = DB::connection('pgsql');
            
            try {
                $supabaseConn->statement('TRUNCATE TABLE "' . $table . '" RESTART IDENTITY CASCADE');
            } catch (\Exception $e) {
                $supabaseConn->table($table)->delete();
            }

            $chunkSize = 100;
            $chunks = array_chunk($data, $chunkSize);

            foreach ($chunks as $chunk) {
                $supabaseConn->table($table)->insert($chunk);
            }

            $this->line("[OK] $table: $count registros sincronizados");

        } catch (\Exception $e) {
            $this->warn("[ERROR] $table: " . $e->getMessage());
        }
    }

    private function checkSupabaseConnection()
    {
        try {
            $result = DB::connection('pgsql')->select('SELECT 1');
            return true;
        } catch (\Exception $e) {
            $this->error('No se puede conectar a Supabase: ' . $e->getMessage());
            throw $e;
        }
    }
}
