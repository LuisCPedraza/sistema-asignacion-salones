<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\N8nWebhookController;
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
Route::prefix('webhooks/n8n')->group(function () {
    // Webhook principal para recibir eventos de n8n
    Route::post('/notify', [N8nWebhookController::class, 'notify']);
    
    // Endpoints para obtener datos (consultados por n8n)
    Route::get('/next-day-assignments', [N8nWebhookController::class, 'getNextDayAssignments']);
    Route::get('/conflicts', [N8nWebhookController::class, 'getConflicts']);
    Route::get('/expiring-guests', [N8nWebhookController::class, 'getExpiringGuests']);
});
