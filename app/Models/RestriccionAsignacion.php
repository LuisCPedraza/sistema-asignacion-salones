<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;  // Para UUID

class RestriccionAsignacion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restricciones_asignacion';  // Tabla 'restricciones_asignación' (español, alinea con migración)

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
        'recurso_type',
        'recurso_id',
        'tipo_restriccion',
        'valor',
        'activo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'valor' => 'array',  // JSON to array
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
     * Relación polimórfica con recurso (Salon or Profesor).
     */
    public function recurso(): MorphTo
    {
        return $this->morphTo('recurso', 'recurso_type', 'recurso_id');
    }

    /**
     * Scope para restricciones activas.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
