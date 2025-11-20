<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asignacion extends Model
{
    protected $table = 'asignaciones';

    public $incrementing = false;        // UUID no es autoincremental
    protected $keyType = 'string';       // PK es string (UUID)

    protected static function boot()
    {
        parent::boot();

        // Generar UUID automáticamente
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    protected $fillable = [
        'grupo_id',
        'salon_id',
        'profesor_id',
        'fecha',
        'hora',
        'hora_fin',
        'estado',
        'score',
        'conflictos',
        'activo',
    ];

    protected $casts = [
        'fecha'      => 'date',
        'hora'       => 'string',
        'hora_fin'   => 'string',
        'conflictos' => 'array',
        'activo'     => 'boolean',
        'score'      => 'decimal:2',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }
}

