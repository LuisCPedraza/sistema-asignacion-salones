<?php

use Illuminate\Support\Facades\Route;
use App\Modules\GestionAcademica\Controllers\StudentGroupController;
use App\Modules\GestionAcademica\Controllers\TeacherController;
use App\Modules\GestionAcademica\Controllers\TeacherAvailabilityController;

// Grupo para Gestión Académica
Route::middleware(['auth'])->prefix('gestion-academica')->name('gestion-academica.')->group(function () {

    // Recursos principales
    Route::resource('student-groups', StudentGroupController::class);
    Route::resource('teachers', TeacherController::class);

    // ✔️ Rutas para disponibilidades de profesores (Administración)
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

    // ⭐ NUEVA RUTA: Autogestión de disponibilidades para profesores ⭐
    Route::get('/my-availabilities', function () {
        $user = auth()->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(404, 'No se encontró un perfil de profesor asociado a tu usuario.');
        }

        return app()->make(TeacherAvailabilityController::class)
            ->callAction('index', [$teacher]);
    })->name('teachers.availabilities.my');

});
