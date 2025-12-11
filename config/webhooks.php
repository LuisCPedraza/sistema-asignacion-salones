<?php

return [
    /*
    |--------------------------------------------------------------------------
    | N8N Webhook URLs
    |--------------------------------------------------------------------------
    |
    | URLs de los webhooks de n8n para notificaciones automáticas.
    | Estos webhooks se disparan cuando ocurren eventos importantes en el sistema.
    |
    */

    // Workflow 1: Notificación cuando se crea una asignación
    'n8n_assignment_created' => env('N8N_WEBHOOK_ASSIGNMENT_CREATED', null),

    // Workflow 6: Notificación cuando se actualiza una asignación
    'n8n_assignment_updated' => env('N8N_WEBHOOK_ASSIGNMENT_UPDATED', null),

    // Workflow 2: Notificación de conflictos horarios detectados
    'n8n_conflicts_detected' => env('N8N_WEBHOOK_CONFLICTS_DETECTED', null),

    // Workflow 3: Notificación de disponibilidades incompletas
    'n8n_incomplete_availabilities' => env('N8N_WEBHOOK_INCOMPLETE_AVAILABILITIES', null),

    /*
    |--------------------------------------------------------------------------
    | Configuración General de Webhooks
    |--------------------------------------------------------------------------
    */

    // Timeout para requests HTTP (segundos)
    'timeout' => env('N8N_WEBHOOK_TIMEOUT', 10),

    // Número de reintentos en caso de fallo
    'retry_attempts' => env('N8N_WEBHOOK_RETRY_ATTEMPTS', 3),

    // Delay entre reintentos (milisegundos)
    'retry_delay' => env('N8N_WEBHOOK_RETRY_DELAY', 100),

    // Habilitar/deshabilitar webhooks globalmente
    'enabled' => env('N8N_WEBHOOKS_ENABLED', true),

    // Registrar webhooks en audit_logs
    'log_to_audit' => env('N8N_WEBHOOKS_LOG_AUDIT', true),
];
