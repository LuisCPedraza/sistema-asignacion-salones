<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $fillable = [
        'user_id',
        'event',
        'entity_id',
        'entity_type',
        'model',
        'model_id',
        'action',
        'changes',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'source',
    ];

    protected $casts = [
        'changes' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELACIONES =====
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== MÉTODOS ESTÁTICOS =====
    
    /**
     * Registrar un cambio en el sistema
     */
    public static function log(
        string $model,
        int|string $modelId,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        $userId = Auth::id();
        
        // No registrar si no hay usuario autenticado
        if (!$userId) {
            return new self();
        }

        $entityType = class_basename($model);
        $changes = null;

        // Solo registrar changes si hay diferencias claras
        if ($oldValues !== null || $newValues !== null) {
            $changes = [
                'old' => $oldValues,
                'new' => $newValues,
            ];
        }

        return self::create([
            'user_id' => $userId,
            'event' => $action, // evento genérico basado en la acción
            'entity_id' => $modelId,
            'entity_type' => $entityType,
            'model' => $entityType,
            'model_id' => $modelId,
            'action' => $action,
            'changes' => $changes,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'source' => 'system',
        ]);
    }

    /**
     * Obtener descripción amigable de la acción
     */
    public function getActionLabel(): string
    {
        $labels = [
            'create' => '✨ Crear',
            'update' => '✏️ Actualizar',
            'delete' => '🗑️ Eliminar',
            'restore' => '♻️ Restaurar',
            'export' => '📥 Exportar',
        ];

        return $labels[$this->action] ?? $this->action;
    }

    /**
     * Obtener cambios formateados para visualización
     */
    public function getFormattedChanges(): array
    {
        $changes = [];

        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
        }

        return $changes;
    }

    /**
     * Obtener cambios en formato texto legible
     */
    public function getReadableChanges(): string
    {
        $changes = $this->getFormattedChanges();
        
        if (empty($changes)) {
            return 'Sin cambios detectados';
        }

        $readable = [];
        foreach ($changes as $field => $change) {
            $readable[] = sprintf(
                '%s: %s → %s',
                $field,
                $change['old'] ?? 'null',
                $change['new'] ?? 'null'
            );
        }

        return implode(', ', $readable);
    }

    /**
     * Obtener filtros disponibles para búsqueda
     */
    public static function getAvailableFilters()
    {
        return [
            'models' => self::distinct()->pluck('model')->sort(),
            'actions' => [
                'create' => '✨ Crear',
                'update' => '✏️ Actualizar',
                'delete' => '🗑️ Eliminar',
                'restore' => '♻️ Restaurar',
                'export' => '📥 Exportar',
            ],
        ];
    }
}
