<?php

namespace App\Traits;

use App\Models\AuditLog;

trait AuditableModel
{
    /**
     * Registrar cambios cuando se crea un modelo
     */
    public static function bootAuditableModel()
    {
        static::created(function ($model) {
            $fillable = $model->getFillable();
            $newValues = !empty($fillable) ? $model->only($fillable) : $model->toArray();
            
            AuditLog::log(
                self::class,
                $model->id,
                'create',
                null,
                $newValues,
                "Creado: {$model->getAuditableDescription()}"
            );
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            if (count($changes) > 0) {
                $oldValues = [];
                $newValues = [];

                foreach ($changes as $key => $value) {
                    if (isset($original[$key])) {
                        $oldValues[$key] = $original[$key];
                    }
                    $newValues[$key] = $value;
                }

                AuditLog::log(
                    self::class,
                    $model->id,
                    'update',
                    $oldValues,
                    $newValues,
                    "Actualizado: {$model->getAuditableDescription()}"
                );
            }
        });

        static::deleted(function ($model) {
            AuditLog::log(
                self::class,
                $model->id,
                'delete',
                $model->toArray(),
                null,
                "Eliminado: {$model->getAuditableDescription()}"
            );
        });
    }

    /**
     * Obtener descripción para auditoría
     * Puede ser sobreescrito en el modelo
     */
    public function getAuditableDescription(): string
    {
        return class_basename($this) . " #{$this->id}";
    }
}
