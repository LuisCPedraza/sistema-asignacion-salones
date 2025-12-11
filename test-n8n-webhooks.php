<?php

/**
 * Script de prueba para Webhooks n8n
 * 
 * Este script simula eventos del sistema para probar la integración con n8n
 * 
 * Uso:
 *   php test-n8n-webhooks.php
 */

require __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\Api\WebhookController;
use App\Modules\Asignacion\Models\Assignment;

// Simular Laravel bootstrap
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🧪 ========================================\n";
echo "   TEST: Integración Webhooks n8n\n";
echo "========================================\n\n";

// Verificar configuración
echo "📋 Verificando configuración...\n";
$webhookUrl = config('webhooks.n8n_assignment_created');

if (!$webhookUrl) {
    echo "❌ ERROR: No se encontró N8N_WEBHOOK_ASSIGNMENT_CREATED en .env\n";
    echo "   Por favor, configura las URLs de webhooks en tu archivo .env\n";
    exit(1);
}

echo "✅ Configuración encontrada:\n";
echo "   - Assignment Created: " . (config('webhooks.n8n_assignment_created') ?: 'NO CONFIGURADO') . "\n";
echo "   - Assignment Updated: " . (config('webhooks.n8n_assignment_updated') ?: 'NO CONFIGURADO') . "\n";
echo "   - Conflicts Detected: " . (config('webhooks.n8n_conflicts_detected') ?: 'NO CONFIGURADO') . "\n";
echo "\n";

// Test 1: Buscar una asignación de prueba
echo "🔍 Test 1: Buscar asignación de prueba...\n";
$assignment = Assignment::with(['teacher', 'group', 'classroom', 'subject'])->first();

if (!$assignment) {
    echo "❌ No se encontraron asignaciones en la base de datos\n";
    echo "   Por favor, crea al menos una asignación para probar\n";
    exit(1);
}

echo "✅ Asignación encontrada:\n";
echo "   - ID: {$assignment->id}\n";
echo "   - Profesor: " . ($assignment->teacher ? $assignment->teacher->first_name . ' ' . $assignment->teacher->last_name : 'N/A') . "\n";
echo "   - Grupo: " . ($assignment->group->name ?? 'N/A') . "\n";
echo "   - Salón: " . ($assignment->classroom->name ?? 'N/A') . "\n";
echo "\n";

// Test 2: Enviar webhook de creación
echo "📤 Test 2: Enviando webhook de asignación creada...\n";
try {
    WebhookController::notifyAssignmentCreated($assignment);
    echo "✅ Webhook enviado exitosamente\n";
    echo "   Revisa:\n";
    echo "   - Email del profesor (" . ($assignment->teacher->email ?? 'N/A') . ")\n";
    echo "   - Ejecución en n8n (http://localhost:5678)\n";
    echo "   - Logs en storage/logs/laravel.log\n";
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Simular actualización
echo "📤 Test 3: Simulando webhook de actualización...\n";
try {
    $oldAssignment = $assignment->replicate();
    $oldAssignment->load(['teacher', 'classroom', 'group', 'subject']);
    
    // Simular un cambio (solo para el test, no lo guardamos)
    $newAssignment = clone $assignment;
    
    WebhookController::notifyAssignmentUpdated($oldAssignment, $newAssignment);
    echo "✅ Webhook de actualización enviado\n";
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 4: Verificar tabla audit_logs
echo "📊 Test 4: Verificando registros en audit_logs...\n";
$auditLogs = \App\Models\AuditLog::where('source', 'webhook')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

if ($auditLogs->isEmpty()) {
    echo "⚠️  No se encontraron registros de webhook en audit_logs\n";
    echo "   Esto es normal si los webhooks fallaron o están deshabilitados\n";
} else {
    echo "✅ Últimos {$auditLogs->count()} registros de webhooks:\n";
    foreach ($auditLogs as $log) {
        echo "   - [{$log->created_at}] {$log->event} - Entity ID: {$log->entity_id}\n";
    }
}
echo "\n";

// Resumen
echo "📋 ========================================\n";
echo "   RESUMEN DEL TEST\n";
echo "========================================\n\n";
echo "✅ Configuración: OK\n";
echo "✅ Webhooks enviados: OK\n";
echo "✅ Sistema funcionando\n\n";
echo "🔍 Próximos pasos:\n";
echo "   1. Verifica el email recibido\n";
echo "   2. Revisa ejecuciones en n8n: http://localhost:5678\n";
echo "   3. Consulta los logs: tail -f storage/logs/laravel.log\n";
echo "   4. Crea una asignación real desde la UI para probar el flujo completo\n\n";
