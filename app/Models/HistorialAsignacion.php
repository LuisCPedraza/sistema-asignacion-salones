<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;  // Para UUID

class HistorialAsignacion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'historial_asignaciones';  // Agregado: Tabla 'historial_asignaciones' (español, alinea with migration)

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
        'asignacion_id',
        'user_id',
        'accion',
        'cambios',
        'fecha',
        'activo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cambios' => 'array',  // JSON to array
        'fecha' => 'datetime',
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
     * Relación with asignación (many to one).
     */
    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id', 'id');
    }

    /**
     * Relación with usuario (many to one).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope for historial activo.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}