<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Infraestructura\Controllers\ClassroomController;
use App\Modules\Infraestructura\Controllers\ClassroomAvailabilityController;

// Grupo para Gestión de Infraestructura
Route::middleware(['auth'])->prefix('infraestructura')->name('infraestructura.')->group(function () {
    // CRUD de Salones
    Route::resource('classrooms', ClassroomController::class);
    
    // Rutas para disponibilidades de salones
    Route::prefix('classrooms/{classroom}')->group(function () {
        Route::get('/availabilities', [ClassroomAvailabilityController::class, 'index'])
            ->name('classrooms.availabilities.index');
        Route::get('/availabilities/create', [ClassroomAvailabilityController::class, 'create'])
            ->name('classrooms.availabilities.create');
        Route::post('/availabilities', [ClassroomAvailabilityController::class, 'store'])
            ->name('classrooms.availabilities.store');
        Route::get('/availabilities/{availability}/edit', [ClassroomAvailabilityController::class, 'edit'])
            ->name('classrooms.availabilities.edit');
        Route::put('/availabilities/{availability}', [ClassroomAvailabilityController::class, 'update'])
            ->name('classrooms.availabilities.update');
        Route::delete('/availabilities/{availability}', [ClassroomAvailabilityController::class, 'destroy'])
            ->name('classrooms.availabilities.destroy');
    });
});