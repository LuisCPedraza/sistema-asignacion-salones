<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('fecha');
            $table->enum('status', ['presente', 'ausente', 'tardanza', 'justificado']);
            $table->string('comment', 500)->nullable();
            $table->foreignId('taken_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id', 'fecha']);
            $table->index(['assignment_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
