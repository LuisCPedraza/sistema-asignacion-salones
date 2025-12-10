<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Controllers\UserController;
use App\Modules\Admin\Controllers\GuestTeachersController;
use App\Modules\Admin\Controllers\ReportController;
use App\Modules\Admin\Controllers\AuditController;
use App\Modules\Admin\Controllers\SystemConfigController;

// Temporal: 'auth' solo
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestión de usuarios (HU1)
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/revoke-guest-access', [UserController::class, 'revokeGuestAccess'])->name('users.revoke-guest-access');
    
    // Profesores Invitados (HU8/HU14)
    Route::get('/guest-teachers', [GuestTeachersController::class, 'index'])->name('guest-teachers.index');
    Route::get('/guest-teachers/create', [GuestTeachersController::class, 'create'])->name('guest-teachers.create');
    Route::post('/guest-teachers', [GuestTeachersController::class, 'store'])->name('guest-teachers.store');
    Route::get('/guest-teachers/{teacher}', [GuestTeachersController::class, 'show'])->name('guest-teachers.show');
    Route::get('/guest-teachers/{teacher}/edit', [GuestTeachersController::class, 'edit'])->name('guest-teachers.edit');
    Route::put('/guest-teachers/{teacher}', [GuestTeachersController::class, 'update'])->name('guest-teachers.update');
    Route::post('/guest-teachers/{teacher}/revoke', [GuestTeachersController::class, 'revoke'])->name('guest-teachers.revoke');
    
    // Reportes (HU15)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/utilization', [ReportController::class, 'utilization'])->name('utilization');
        Route::get('/statistics', [ReportController::class, 'statistics'])->name('statistics');
        
        // Exportación PDF (HU13)
        Route::get('/export/general-pdf', [ReportController::class, 'exportGeneralPdf'])->name('export.general.pdf');
        Route::get('/export/utilization-pdf', [ReportController::class, 'exportUtilizationPdf'])->name('export.utilization.pdf');
        Route::get('/export/statistics-pdf', [ReportController::class, 'exportStatisticsPdf'])->name('export.statistics.pdf');
    });
    
    // Auditoría (HU18)
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/{auditLog}', [AuditController::class, 'show'])->name('audit.show');
    
    // Configuración del sistema (HU19)
    Route::get('/config', [SystemConfigController::class, 'index'])->name('config.index');
    Route::get('/config/edit', [SystemConfigController::class, 'edit'])->name('config.edit');
    Route::put('/config', [SystemConfigController::class, 'update'])->name('config.update');
});
