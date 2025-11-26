<?php

use Illuminate\Support\Facades\Route;
use App\Modules\GestionAcademica\Controllers\StudentGroupController;

// Grupo para Gestión Académica - solución temporal sin middleware role
Route::middleware(['auth'])->prefix('gestion-academica')->name('gestion-academica.')->group(function () {
    Route::resource('student-groups', StudentGroupController::class);  // CRUD resource (HU3/HU4)
});