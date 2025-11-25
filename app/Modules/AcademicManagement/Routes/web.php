<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Academic\StudentGroupController;
use App\Http\Controllers\Academic\TeacherController;
use App\Http\Controllers\Academic\AssignmentController;  // ← Importar

// Grupo de rutas para Academic Management (protegido por auth y role)
Route::middleware(['auth', 'role:coordinador'])->prefix('academic')->name('academic.')->group(function () {
    // Rutas existentes (student-groups, teachers) ya están, agrégales esta:
    Route::resource('assignments', AssignmentController::class);  // ← Nueva: Crea index, create, etc.
    
    // Ruta para dashboard (si no está en otro lugar)
    Route::get('/dashboard', function () {
        return view('academic.dashboard');
    })->name('dashboard');
});