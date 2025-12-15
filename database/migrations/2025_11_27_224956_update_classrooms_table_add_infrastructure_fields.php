<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // Agregar campos para HU5
            $table->string('name')->after('id'); // Nombre del salón, ej: "Aula 101"
            $table->string('code')->unique()->after('name'); // Código único, ej: "A101"
            $table->integer('capacity')->default(30)->after('code');
            $table->json('resources')->nullable()->after('capacity'); // ['proyector', 'computadoras', 'pizarra_inteligente', etc]
            $table->string('location')->nullable()->after('resources'); // Edificio, piso, etc.
            $table->text('special_features')->nullable()->after('location'); // Características especiales
            $table->boolean('is_active')->default(true)->after('special_features');
            $table->text('restrictions')->nullable()->after('is_active'); // Restricciones de uso
            $table->enum('type', ['aula', 'laboratorio', 'auditorio', 'sala_reuniones', 'taller', 'cancha_deportiva'])->default('aula')->after('restrictions');
            $table->integer('floor')->default(1)->after('type'); // Piso
            $table->string('wing')->nullable()->after('floor'); // Ala o sector
            
            // Si no existe la relación con buildings, agregarla
            if (!Schema::hasColumn('classrooms', 'building_id')) {
                $table->foreignId('building_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'code',
                'capacity', 
                'resources', 
                'location', 
                'special_features',
                'is_active',
                'restrictions',
                'type',
                'floor',
                'wing'
            ]);
            
            if (Schema::hasColumn('classrooms', 'building_id')) {
                $table->dropForeign(['building_id']);
                $table->dropColumn('building_id');
            }
        });
    }
};