<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Agregar career_id
            $table->foreignId('career_id')->nullable()->after('id')->constrained('careers')->onDelete('cascade');
            
            // Cambiar el código a no único (será único por combinación de code + career_id)
            $table->dropUnique('subjects_code_unique');
            $table->unique(['code', 'career_id'], 'subjects_code_career_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropUnique('subjects_code_career_unique');
            $table->unique('code', 'subjects_code_unique');
            $table->dropForeignIdFor(\App\Models\Career::class);
        });
    }
};
