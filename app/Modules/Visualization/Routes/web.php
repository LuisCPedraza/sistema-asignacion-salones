<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Visualization\Controllers\HorarioController;

// Agrupar las rutas de horario
Route::prefix('horario')->name('horario.')->group(function () {
    // HU14: Horario personal (Profesores y Coordinadores)
    Route::middleware(['auth', 'role:profesor,profesor_invitado,coordinador,secretaria_coordinacion'])->group(function () {
        Route::get('/personal', [HorarioController::class, 'personal'])
            ->name('personal');

        Route::get('/personal/export', [HorarioController::class, 'exportPersonal'])
            ->name('personal.export');
    });

    // HU13: Horario semestral (Coordinadores)
    Route::middleware(['auth', 'role:coordinador,secretaria_coordinacion'])->group(function () {
        Route::get('/semestral', [HorarioController::class, 'semestral'])
            ->name('semestral');

        Route::get('/semestral/export', [HorarioController::class, 'exportSemestral'])
            ->name('semestral.export');

        // Nueva vista: Malla horaria tipo cuadrícula
        Route::get('/malla-semestral', [HorarioController::class, 'mallaSemestral'])
            ->name('malla-semestral');
    });

});