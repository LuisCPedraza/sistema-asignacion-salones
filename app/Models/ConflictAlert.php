<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\GestionAcademica\Models\StudentGroup;

class ConflictAlert extends Model
{
    protected $table = 'conflict_alerts';
    
    protected $fillable = [
        'conflict_type',
        'severity',
        'assignment_id',
        'classroom_id',
        'teacher_id',
        'student_group_id',
        'description',
        'conflict_details',
        'day',
        'start_time',
        'end_time',
        'status',
        'notified_at',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'conflict_details' => 'array',
        'notified_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELACIONES =====
    
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function studentGroup(): BelongsTo
    {
        return $this->belongsTo(StudentGroup::class);
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // ===== SCOPES =====
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeNotified($query)
    {
        return $query->where('status', 'notified');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    // ===== MÉTODOS =====
    
    /**
     * Marcar como notificada
     */
    public function markAsNotified(): void
    {
        $this->update([
            'status' => 'notified',
            'notified_at' => now(),
        ]);
    }

    /**
     * Marcar como resuelta
     */
    public function markAsResolved(string $notes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Obtener etiqueta de severidad con emoji
     */
    public function getSeverityLabel(): string
    {
        $labels = [
            'low' => '🟢 Baja',
            'medium' => '🟡 Media',
            'high' => '🟠 Alta',
            'critical' => '🔴 Crítica',
        ];

        return $labels[$this->severity] ?? $this->severity;
    }

    /**
     * Obtener etiqueta de tipo de conflicto
     */
    public function getTypeLabel(): string
    {
        $labels = [
            'room_double_booking' => 'Salón con múltiples asignaciones',
            'teacher_overlap' => 'Profesor en múltiples lugares',
            'room_unavailable' => 'Salón no disponible',
            'capacity_exceeded' => 'Capacidad excedida',
            'teacher_unavailable' => 'Profesor no disponible',
        ];

        return $labels[$this->conflict_type] ?? $this->conflict_type;
    }

    /**
     * Obtener etiqueta de estado
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => '⏳ Pendiente',
            'notified' => '📧 Notificado',
            'resolved' => '✅ Resuelto',
            'ignored' => '🚫 Ignorado',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
