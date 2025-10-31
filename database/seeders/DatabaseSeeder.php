<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Grupo;  // Agregado para seed grupos
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin con password 'password123' (para login en browser)
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),  // Hash para 'password123' (CRUD)
            'rol' => 'admin',
        ]);

        // Seed ejemplos para roles ampliados (para tests y CRUD)
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
            'name' => 'Coordinador Infra Test',
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

        // Factory para 5 users genéricos con roles random
        User::factory(5)->create();

        // Seed ejemplos para grupos (HU3/HU4)
        Grupo::factory(2)->create();  // 2 grupos random con factory (nombre unique, nivel random, num >0, activo true)

        // Seeders adicionales (si hay)
        $this->call([
            // Otros seeders, e.g., SalonSeeder::class
        ]);
    }
}