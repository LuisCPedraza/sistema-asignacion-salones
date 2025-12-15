<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_schedules', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->after('semester_id')->constrained('teachers')->onDelete('set null');
            $table->string('jornada', 10)->nullable()->after('teacher_id'); // DIU / NOC
            $table->integer('cupo')->nullable()->after('jornada');
        });
    }

    public function down(): void
    {
        Schema::table('course_schedules', function (Blueprint $table) {
            $table->dropColumn(['cupo','jornada']);
            $table->dropConstrainedForeignId('teacher_id');
        });
    }
};
