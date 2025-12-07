<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insertar algunos períodos académicos de ejemplo
        DB::table('academic_periods')->insert([
            [
                'name' => 'Primer Semestre 2024',
                'start_date' => '2024-01-15',
                'end_date' => '2024-06-30',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Segundo Semestre 2024',
                'start_date' => '2024-07-15',
                'end_date' => '2024-12-20',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Primer Semestre 2025',
                'start_date' => '2025-01-13',
                'end_date' => '2025-06-28',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_periods');
    }
};
