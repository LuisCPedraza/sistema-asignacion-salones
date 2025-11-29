<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Controllers\UserController;
use App\Modules\Admin\Controllers\ReportController;
use App\Modules\Admin\Controllers\AuditController;
use App\Modules\Admin\Controllers\SystemConfigController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestión de usuarios (HU1)
    Route::resource('users', UserController::class);
    
    // Reportes (HU15)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/utilization', [ReportController::class, 'utilization'])->name('utilization');
        Route::get('/statistics', [ReportController::class, 'statistics'])->name('statistics');
    });
    
    // Auditoría (HU18)
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/{id}', [AuditController::class, 'show'])->name('audit.show');
    
    // Configuración del sistema (HU19)
    Route::get('/config', [SystemConfigController::class, 'index'])->name('config.index');
    Route::get('/config/edit', [SystemConfigController::class, 'edit'])->name('config.edit');
    Route::put('/config', [SystemConfigController::class, 'update'])->name('config.update');
});