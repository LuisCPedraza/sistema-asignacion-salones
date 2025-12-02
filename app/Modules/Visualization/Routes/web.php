<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Visualization\Controllers\HorarioController;

Route::middleware(['auth'])->group(function () {
    // HU13: Horario semestral (solo coordinadores)
    Route::middleware(['role:coordinador'])->group(function () {
        Route::get('/horario-semestral', [HorarioController::class, 'semestral'])
            ->name('horario.semestral');  // ← Nombre correcto
        Route::get('/horario-semestral/export', [HorarioController::class, 'exportSemestral'])
            ->name('horario.semestral.export');
    });

    // HU14: Horario personal (profesores e invitados)
    Route::middleware(['role:profesor', 'role:invitado'])->group(function () {
        Route::get('/horario-personal', [HorarioController::class, 'personal'])
            ->name('horario.personal');
        Route::get('/horario-personal/export', [HorarioController::class, 'exportPersonal'])
            ->name('horario.personal.export');
    });
});