<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Asignacion\Controllers\AssignmentController;
use App\Modules\Asignacion\Controllers\AssignmentRuleController;

Route::middleware([
    'auth',
    'role:coordinador,secretaria_coordinacion'
])->group(function () {

    Route::get('/', [AssignmentController::class, 'index'])
        ->name('asignacion.index');

    Route::get('/automatica', [AssignmentController::class, 'automatica'])
        ->name('asignacion.automatica');

    Route::post('/automatica/ejecutar', [AssignmentController::class, 'ejecutarAutomatica'])
        ->name('asignacion.ejecutar-automatica');

    Route::get('/manual', [AssignmentController::class, 'manual'])
        ->name('asignacion.manual');

    Route::get('/conflictos', [AssignmentController::class, 'conflictos'])
        ->name('asignacion.conflictos');

    Route::get('/reglas', [AssignmentRuleController::class, 'index'])
        ->name('asignacion.reglas');

    Route::post('/reglas/actualizar', [AssignmentRuleController::class, 'actualizar'])
        ->name('asignacion.reglas.actualizar');

    Route::post('/reglas/{id}/toggle', [AssignmentRuleController::class, 'toggle'])
        ->name('asignacion.reglas.toggle');
});