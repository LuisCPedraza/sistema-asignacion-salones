<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AsignacionController;
use App\Http\Controllers\Admin\AsignacionVisualizacionController;  // Añadido: para la visualización

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grupo para admin (protegido por auth)
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin,coordinador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // 👉 Ruta para visualización usando el controlador específico
        Route::get('/asignaciones/visualizacion', 
            [AsignacionVisualizacionController::class, 'index']
        )->name('asignaciones.visualizacion');

        // 👉 La visualización existente (mantener por compatibilidad)
        Route::get('/asignaciones/visualizacion-original', 
            [AsignacionController::class, 'visualizacion']
        )->name('asignaciones.visualizacion.original');

        // Rutas CRUD
        Route::resource('users', UserController::class);
        Route::resource('grupos', \App\Http\Controllers\Admin\GrupoController::class);
        Route::resource('salones', \App\Http\Controllers\Admin\SalonController::class)
            ->parameters(['salones' => 'salon']);
        Route::resource('profesores', \App\Http\Controllers\Admin\ProfesorController::class)
            ->parameters(['profesores' => 'profesor']);
        Route::resource('configuraciones', \App\Http\Controllers\Admin\ConfiguracionController::class)
            ->parameters(['configuraciones' => 'configuracion']);

        // 👉 Resource de asignaciones
        Route::resource('asignaciones', \App\Http\Controllers\Admin\AsignacionController::class)
            ->parameters(['asignaciones' => 'asignacion']);

        Route::resource('propuestas_asignacion', \App\Http\Controllers\Admin\PropuestaAsignacionController::class)
            ->parameters(['propuestas_asignacion' => 'propuestaAsignacion']);

        Route::resource('logs_visualizacion', \App\Http\Controllers\Admin\LogVisualizacionController::class)
            ->parameters(['logs_visualizacion' => 'logVisualizacion']);

        Route::resource('restricciones_asignacion', \App\Http\Controllers\Admin\RestriccionAsignacionController::class)
            ->parameters(['restricciones_asignacion' => 'restriccionAsignacion']);

        Route::resource('historial_asignacion', \App\Http\Controllers\Admin\HistorialAsignacionController::class)
            ->parameters(['historial_asignacion' => 'historialAsignacion']);

        //Route::post('/admin/asignaciones', [AsignacionController::class, 'store'])
        //    ->name('admin.asignaciones.store');
});

Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':profesor'])->prefix('profesor')->name('profesor.')->group(function () {
    Route::get('/perfil', function () { return view('profesor.perfil'); })->name('perfil');
});

Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':coordinador'])->prefix('coordinador')->name('coordinador.')->group(function () {
    Route::get('/asignaciones', function () { return view('coordinador.asignaciones'); })->name('asignaciones');
});

Route::get('/debug-ruta', function () {
    return 'Ruta /admin/asignaciones/visualizacion está activa';
});

// Ruta de test para aislar el problema
Route::get('/test-visualizacion', function () {
    return 'Test OK - Si ves esto, la ruta funciona';
})->middleware('auth');

require __DIR__.'/auth.php';