<?php

use Illuminate\Support\Facades\Route;
use App\Modules\GestionAcademica\Controllers\StudentGroupController;
use App\Modules\GestionAcademica\Controllers\TeacherController;
use App\Modules\GestionAcademica\Controllers\TeacherAvailabilityController;

// Grupo para Gestión Académica (Temporal: 'auth' solo)
Route::middleware(['auth'])->prefix('gestion-academica')->name('gestion-academica.')->group(function () {
    Route::resource('student-groups', StudentGroupController::class);
    Route::resource('teachers', TeacherController::class);

    // Disponibilidades de profesores (CRUD completo)
    Route::prefix('teachers/{teacher}')->group(function () {
        Route::get('/availabilities', [TeacherAvailabilityController::class, 'index'])->name('teachers.availabilities.index');
        Route::get('/availabilities/create', [TeacherAvailabilityController::class, 'create'])->name('teachers.availabilities.create');
        Route::post('/availabilities', [TeacherAvailabilityController::class, 'store'])->name('teachers.availabilities.store');
        Route::get('/availabilities/{availability}/edit', [TeacherAvailabilityController::class, 'edit'])->name('teachers.availabilities.edit');
        Route::put('/availabilities/{availability}', [TeacherAvailabilityController::class, 'update'])->name('teachers.availabilities.update');
        Route::delete('/availabilities/{availability}', [TeacherAvailabilityController::class, 'destroy'])->name('teachers.availabilities.destroy');
    });
});

// Módulo 8: Portal Profesores (HU8, HU14) - Acceso limitado para profesores/invitados
Route::middleware(['auth'])->prefix('gestion-academica')->name('gestion-academica.')->group(function () {  // Temporal 'auth'
    Route::get('/my-availabilities', [TeacherAvailabilityController::class, 'myAvailabilities'])->name('teachers.availabilities.my');
});