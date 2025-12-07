<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Visualization\Controllers\HorarioController;

Route::middleware(['auth'])->group(function () {
    // HU13: Horario semestral (solo coordinadores)
    Route::get('/horario-semestral', [HorarioController::class, 'semestral'])
        ->name('horario.semestral')
        ->middleware('role:coordinador');
    
    Route::get('/horario-semestral/export', [HorarioController::class, 'exportSemestral'])
        ->name('horario.semestral.export')
        ->middleware('role:coordinador');

    // HU14: Horario personal (profesores e invitados)
    Route::get('/horario-personal', [HorarioController::class, 'personal'])
        ->name('horario.personal')
        ->middleware('role:profesor,profesor_invitado');
    
    Route::get('/horario-personal/export', [HorarioController::class, 'exportPersonal'])
        ->name('horario.personal.export')
        ->middleware('role:profesor,profesor_invitado');
});