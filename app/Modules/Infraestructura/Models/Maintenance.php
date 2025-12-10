<?php

namespace App\Modules\Infraestructura\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'maintenances';

    protected static function newFactory()
    {
        return \Database\Factories\Modules\Infraestructura\MaintenanceFactory::new();
    }

    protected $fillable = [
        'classroom_id',
        'type',
        'title',
        'description',
        'status',
        'scheduled_date',
        'start_date',
        'end_date',
        'responsible',
        'notes',
        'cost',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'cost' => 'decimal:2',
    ];

    /**
     * Relación con Classroom
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Infraestructura\Models\Classroom::class);
    }

    /**
     * Scopes para filtros comunes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'en_progreso');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completado');
    }

    public function scopePreventive($query)
    {
        return $query->where('type', 'preventivo');
    }

    public function scopeCorrective($query)
    {
        return $query->where('type', 'correctivo');
    }

    /**
     * Accesores para propiedades calculadas
     */
    public function getDurationAttribute(): ?string
    {
        if ($this->start_date && $this->end_date) {
            $diff = $this->end_date->diffForHumans($this->start_date, ['parts' => 2]);
            return $diff;
        }
        return null;
    }

    /**
     * Método para marcar como completado
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completado',
            'end_date' => now(),
        ]);
    }

    /**
     * Método para marcar como en progreso
     */
    public function markAsInProgress(): void
    {
        $this->update([
            'status' => 'en_progreso',
            'start_date' => now(),
        ]);
    }
}
