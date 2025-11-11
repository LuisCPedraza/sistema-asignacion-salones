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
        Schema::table('salones', function (Blueprint $table) {
            $table->json('recursos')->nullable();  // JSON nullable for horarios and resources
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salones', function (Blueprint $table) {
            $table->dropColumn('recursos');
        });
    }
};