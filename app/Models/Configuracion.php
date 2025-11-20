<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'key',
        'value',
        'activo',
    ];

    protected $casts = [
        'value' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Generación automática de UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Scope para configuraciones activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
