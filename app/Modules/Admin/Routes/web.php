<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\SystemConfigController;
use App\Http\Middleware\AdminMiddleware;

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestión de usuarios
    Route::resource('users', UserController::class);
    
    // Reportes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/utilization', [ReportController::class, 'utilization'])->name('utilization');
        Route::get('/statistics', [ReportController::class, 'statistics'])->name('statistics');
    });
    
    // Auditoría
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/{id}', [AuditController::class, 'show'])->name('audit.show');
    
    // Configuración del sistema
    Route::get('/config', [SystemConfigController::class, 'index'])->name('config.index');
    Route::get('/config/edit', [SystemConfigController::class, 'edit'])->name('config.edit');
    Route::put('/config', [SystemConfigController::class, 'update'])->name('config.update');
});