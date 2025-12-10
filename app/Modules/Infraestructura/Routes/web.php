<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Infraestructura\Controllers\ClassroomController;
use App\Modules\Infraestructura\Controllers\ClassroomAvailabilityController;
use App\Modules\Infraestructura\Controllers\MaintenanceController;
use App\Modules\Infraestructura\Controllers\ReservationController;
use App\Modules\Infraestructura\Controllers\DashboardController;
use App\Modules\Infraestructura\Controllers\ReportsController;

// Grupo para Gestión de Infraestructura (Temporal: 'auth' solo)
Route::middleware(['auth'])->prefix('infraestructura')->name('infraestructura.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // CRUD de Mantenimiento (HU20)
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/mark-in-progress', [MaintenanceController::class, 'markInProgress'])
        ->name('maintenance.mark-in-progress');
    Route::post('/maintenance/{maintenance}/mark-completed', [MaintenanceController::class, 'markCompleted'])
        ->name('maintenance.mark-completed');

    // CRUD de Reservas (HU21)
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{reservation}/approve', [ReservationController::class, 'approve'])
        ->name('reservations.approve');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])
        ->name('reservations.reject');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');

    // Reportes (HU22)
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
});
