<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            // Agregar campos para infraestructura
            $table->string('name')->after('id'); // Agregar columna name
            $table->string('code')->unique()->after('name');
            $table->string('location')->after('code');
            $table->integer('floors')->default(1)->after('location');
            $table->text('description')->nullable()->after('floors');
            $table->json('facilities')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('facilities');
        });
    }

    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn(['name', 'code', 'location', 'floors', 'description', 'facilities', 'is_active']);
        });
    }
};