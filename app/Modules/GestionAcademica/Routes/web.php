<?php

use Illuminate\Support\Facades\Route;
use App\Modules\GestionAcademica\Controllers\StudentGroupController;
use App\Modules\GestionAcademica\Controllers\TeacherController;
use App\Modules\GestionAcademica\Controllers\TeacherAvailabilityController;
use App\Modules\GestionAcademica\Controllers\ReportsController;

// === RUTAS PARA COORDINADORES (CRUD completo) ===
Route::middleware(['auth', 'role:coordinador,secretaria_coordinacion'])
    ->prefix('gestion-academica')
    ->name('gestion-academica.')
    ->group(function () {

        Route::resource('student-groups', StudentGroupController::class);
        Route::resource('teachers', TeacherController::class);
        
        // Reportes Académicos
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
    });

// === DISPONIBILIDADES DE PROFESORES (Coordinadores + Profesores) ===
Route::middleware(['auth', 'role:coordinador,secretaria_coordinacion,profesor,profesor_invitado'])
    ->prefix('gestion-academica')
    ->name('gestion-academica.')
    ->group(function () {
        // Disponibilidades de profesores (CRUD)
        Route::prefix('teachers/{teacher}')->group(function () {
            Route::get('/availabilities', [TeacherAvailabilityController::class, 'index'])
                ->name('teachers.availabilities.index');
            Route::get('/availabilities/create', [TeacherAvailabilityController::class, 'create'])
                ->name('teachers.availabilities.create');
            Route::post('/availabilities', [TeacherAvailabilityController::class, 'store'])
                ->name('teachers.availabilities.store');
            Route::get('/availabilities/{availability}/edit', [TeacherAvailabilityController::class, 'edit'])
                ->name('teachers.availabilities.edit');
            Route::put('/availabilities/{availability}', [TeacherAvailabilityController::class, 'update'])
                ->name('teachers.availabilities.update');
            Route::delete('/availabilities/{availability}', [TeacherAvailabilityController::class, 'destroy'])
                ->name('teachers.availabilities.destroy');
        });
    });

// === RUTA PARA PROFESORES: Mis Disponibilidades ===
Route::middleware(['auth', 'role:profesor,profesor_invitado'])
    ->prefix('gestion-academica')
    ->name('gestion-academica.')
    ->group(function () {
        Route::get('/my-availabilities', [TeacherAvailabilityController::class, 'myAvailabilities'])
            ->name('teachers.availabilities.my');
    });