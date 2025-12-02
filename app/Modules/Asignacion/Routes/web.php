<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Asignacion\Controllers\AssignmentController;
use App\Modules\Asignacion\Controllers\AssignmentRuleController;

// Grupo protegido solo para coordinador y secretaria de coordinación
Route::middleware([
    'auth',
    'role:coordinador|secretaria_coordinacion'
])->group(function () {

    // Ruta principal del módulo (landing)
    Route::get('/', [AssignmentController::class, 'index'])
        ->name('index');  // ← QUITÉ 'asignacion.' del nombre

    // HU9: Asignación Automática
    Route::get('/automatica', [AssignmentController::class, 'automatica'])
        ->name('automatica');  // ← QUITÉ 'asignacion.' del nombre

    Route::post('/automatica/ejecutar', [AssignmentController::class, 'ejecutarAutomatica'])
        ->name('ejecutar-automatica');  // ← QUITÉ 'asignacion.' del nombre

    // HU11: Asignación Manual
    Route::get('/manual', [AssignmentController::class, 'manual'])
        ->name('manual');  // ← QUITÉ 'asignacion.' del nombre

    // HU12: Conflictos
    Route::get('/conflictos', [AssignmentController::class, 'conflictos'])
        ->name('conflictos');  // ← QUITÉ 'asignacion.' del nombre

    // HU10: Reglas
    Route::get('/reglas', [AssignmentRuleController::class, 'index'])
        ->name('reglas');  // ← QUITÉ 'asignacion.' del nombre
    Route::post('/reglas/actualizar', [AssignmentRuleController::class, 'actualizar'])
        ->name('reglas.actualizar');  // ← QUITÉ 'asignacion.' del nombre
    Route::post('/reglas/{id}/toggle', [AssignmentRuleController::class, 'toggle'])
        ->name('reglas.toggle');  // ← QUITÉ 'asignacion.' del nombre
});