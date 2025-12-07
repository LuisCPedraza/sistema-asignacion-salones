<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_availabilities', function (Blueprint $table) {
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->after('teacher_id');
        });

        Schema::table('classroom_availabilities', function (Blueprint $table) {
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->after('classroom_id');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_availabilities', function (Blueprint $table) {
            $table->dropColumn('day');
        });

        Schema::table('classroom_availabilities', function (Blueprint $table) {
            $table->dropColumn('day');
        });
    }
};