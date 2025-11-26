<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insertar roles básicos si no existen
        $roles = [
            ['name' => 'Administrador', 'slug' => 'administrador', 'description' => 'Administrador del sistema', 'is_active' => true],
            ['name' => 'Secretaria Administrativa', 'slug' => 'secretaria_administrativa', 'description' => 'Secretaria administrativa', 'is_active' => true],
            ['name' => 'Coordinador', 'slug' => 'coordinador', 'description' => 'Coordinador académico', 'is_active' => true],
            ['name' => 'Secretaria de Coordinación', 'slug' => 'secretaria_coordinacion', 'description' => 'Secretaria de coordinación', 'is_active' => true],
            ['name' => 'Coordinador de Infraestructura', 'slug' => 'coordinador_infraestructura', 'description' => 'Coordinador de infraestructura', 'is_active' => true],
            ['name' => 'Secretaria de Infraestructura', 'slug' => 'secretaria_infraestructura', 'description' => 'Secretaria de infraestructura', 'is_active' => true],
            ['name' => 'Profesor', 'slug' => 'profesor', 'description' => 'Profesor regular', 'is_active' => true],
            ['name' => 'Profesor Invitado', 'slug' => 'profesor_invitado', 'description' => 'Profesor invitado temporal', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                $role
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};