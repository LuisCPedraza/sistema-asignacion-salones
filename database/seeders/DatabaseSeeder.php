<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Grupo;
use App\Models\Salon;
use App\Models\Profesor;
use App\Models\Asignacion;
use App\Models\Configuracion;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * ================================================================
         * USUARIOS MANUALES PRINCIPALES
         * ================================================================
         */
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin',
        ]);

        User::create([
            'name' => 'Super Admin Test',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'superadmin',
        ]);

        User::create([
            'name' => 'Secretaria Test',
            'email' => 'secretaria@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'secretaria',
        ]);

        User::create([
            'name' => 'Coordinador Infraestructura',
            'email' => 'coord_infra@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'coordinador_infra',
        ]);

        User::create([
            'name' => 'Profesor Test',
            'email' => 'profesor@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'profesor',
        ]);

        // 5 usuarios aleatorios
        User::factory(5)->create();


        /**
         * ================================================================
         * DATOS MASIVOS DE PRUEBA
         * ================================================================
         */
        $grupos = Grupo::factory(10)->create();
        $salones = Salon::factory(10)->create();
        $profesores = Profesor::factory(10)->create();


        /**
         * ================================================================
         * CONFIGURACIONES DEL SISTEMA (corrigidas para usar key / value JSON)
         * ================================================================
         */
        Configuracion::create([
            'key' => 'horario_inicio',
            'value' => ['hora' => '07:00'],
            'activo' => true,
        ]);

        Configuracion::create([
            'key' => 'horario_fin',
            'value' => ['hora' => '18:00'],
            'activo' => true,
        ]);

        Configuracion::create([
            'key' => 'duracion_bloque',
            'value' => ['minutos' => 60],
            'activo' => true,
        ]);

        Configuracion::create([
            'key' => 'dias_laborales',
            'value' => [
                'dias' => ['lunes', 'martes', 'miercoles', 'jueves', 'viernes']
            ],
            'activo' => true,
        ]);

        Configuracion::create([
            'key' => 'periodo_actual',
            'value' => ['semestre' => '2025-2'],
            'activo' => true,
        ]);


        /**
         * ================================================================
         * ASIGNACIONES DE PRUEBA (20)
         * ================================================================
         */
        foreach (range(1, 20) as $i) {
            Asignacion::create([
                'grupo_id'     => $grupos->random()->id,
                'salon_id'     => $salones->random()->id,
                'profesor_id'  => $profesores->random()->id,
                'dia_semana'   => collect(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'])->random(),
                'hora_inicio'  => sprintf('%02d:00:00', rand(7, 15)),
                'hora_fin'     => sprintf('%02d:00:00', rand(8, 18)),
                'estado'       => 'confirmada',
                'activo'       => 1,
            ]);
        }
    }
}

