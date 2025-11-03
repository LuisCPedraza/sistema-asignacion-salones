<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;  // Para UUID

class Asignacion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asignaciones';  // Fix: Tabla 'asignaciones' (español, alinea con migración)

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;  // False para UUID string (no int auto-increment)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'grupo_id',
        'salon_id',
        'profesor_id',
        'fecha',
        'hora',
        'estado',
        'score',
        'conflictos',
        'activo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i',  // Cambiado: 'datetime:H:i' for time string (e.g., "08:00")
        'score' => 'decimal:2',
        'conflictos' => 'array',  // JSON to array
        'activo' => 'boolean',
    ];

    /**
     * Boot method for UUID generation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();  // Genera UUID para id
            }
        });
    }

    /**
     * Relación con grupo (many to one).
     */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id', 'id');
    }

    /**
     * Relación con salón (many to one).
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'id');
    }

    /**
     * Relación con profesor (many to one).
     */
    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'profesor_id', 'id');
    }

    /**
     * Scope para asignaciones activas.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
