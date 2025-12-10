<?php

use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas API para integración con n8n y servicios externos
|
*/

// Rutas para n8n (requieren token de API)
Route::prefix('n8n')->group(function () {
    // Obtener clases del día siguiente para envío de correos
    Route::get('/tomorrow-classes', [NotificationController::class, 'getTomorrowClasses']);
    
    // Obtener estadísticas diarias para informe al admin
    Route::get('/daily-stats', [NotificationController::class, 'getDailyStats']);
    
    // Obtener conflictos detectados
    Route::get('/conflicts', [NotificationController::class, 'getConflicts']);
});
