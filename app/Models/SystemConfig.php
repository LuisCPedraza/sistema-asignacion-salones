<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class SystemConfig extends Model
{
    protected $table = 'system_configs';

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener un parámetro de configuración por clave
     */
    public static function get($key, $default = null)
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    /**
     * Establecer un parámetro de configuración
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    /**
     * Obtener todos los parámetros como array clave-valor
     */
    public static function getAllAsKeyValue()
    {
        return static::query()
            ->get()
            ->keyBy('key')
            ->transform(fn($item) => $item->value)
            ->toArray();
    }

    /**
     * Obtener configuraciones agrupadas por sección
     */
    public static function grouped()
    {
        return static::query()
            ->get()
            ->groupBy(fn($item) => explode('.', $item->key)[0])
            ->toArray();
    }
}
