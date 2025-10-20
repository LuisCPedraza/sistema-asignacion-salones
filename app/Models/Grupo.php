<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;  // Agrega use para UUID

class Grupo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'nivel',
        'num_estudiantes',
        'activo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
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
     * Relación con asignaciones (uno a muchos).
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class);
    }

    /**
     * Scope para grupos activos.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
