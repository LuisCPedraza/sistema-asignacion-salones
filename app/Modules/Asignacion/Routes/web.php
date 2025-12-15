<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Asignacion\Controllers\AssignmentController;
use App\Modules\Asignacion\Controllers\AssignmentRuleController;
use App\Modules\Asignacion\Controllers\TeacherScheduleController;

Route::middleware([
    'auth',
    'role:coordinador,secretaria_coordinacion'
])->group(function () {

    Route::get('/', [AssignmentController::class, 'index'])
        ->name('index');

    Route::get('/automatica', [AssignmentController::class, 'automatica'])
        ->name('automatica');

    Route::post('/automatica/ejecutar', [AssignmentController::class, 'ejecutarAutomatica'])
        ->name('ejecutar-automatica');

    Route::get('/resultados', [AssignmentController::class, 'resultados'])
        ->name('resultados');

    Route::get('/manual', [AssignmentController::class, 'manual'])
        ->name('manual');

    Route::get('/manual/pdf', [AssignmentController::class, 'exportManualPdf'])
        ->name('manual.pdf');

    // Rutas API para asignación manual drag & drop (HU11)
    Route::post('/manual/store', [AssignmentController::class, 'storeManual'])
        ->name('manual.store');
    
    Route::put('/manual/{assignment}', [AssignmentController::class, 'updateManual'])
        ->name('manual.update');
    
    Route::delete('/manual/{assignment}', [AssignmentController::class, 'destroyManual'])
        ->name('manual.destroy');

    Route::get('/conflictos', [AssignmentController::class, 'conflictos'])
        ->name('conflictos');

    Route::get('/reglas', [AssignmentRuleController::class, 'index'])
        ->name('reglas');

    Route::post('/reglas/actualizar', [AssignmentRuleController::class, 'actualizar'])
        ->name('reglas.actualizar');

    Route::post('/reglas/{rule}/toggle', [AssignmentRuleController::class, 'toggle'])
        ->name('reglas.toggle');
});

// Rutas privadas para profesores (HU14)
Route::middleware('auth')->group(function () {
    Route::get('/mi-horario', [TeacherScheduleController::class, 'mySchedule'])
        ->name('teacher.schedule');
    
    Route::get('/mi-horario/descargar', [TeacherScheduleController::class, 'downloadSchedule'])
        ->name('teacher.schedule.download');
});
