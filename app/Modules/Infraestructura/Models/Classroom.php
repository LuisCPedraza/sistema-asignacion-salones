<?php

namespace App\Modules\Infraestructura\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'resources',
        'location',
        'special_features',
        'is_active',
        'restrictions',
        'type',
        'floor',
        'wing',
        'building_id'
    ];

    protected $casts = [
        'resources' => 'array',
        'is_active' => 'boolean'
    ];

    protected static function newFactory()
    {
        return \Database\Factories\Modules\Infraestructura\ClassroomFactory::new();
    }

    // Relación con edificio
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // Relación con disponibilidades
    public function availabilities()
    {
        return $this->hasMany(ClassroomAvailability::class);
    }

    // Scope para salones activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Métodos de utilidad
    public function getAvailableSlotsAttribute()
    {
        return $this->availabilities()->where('is_available', true)->get();
    }

    public function getResourcesListAttribute()
    {
        // Asegurarse de que resources sea un array antes de usar implode
        $resources = $this->resources;
        if (is_string($resources)) {
            $resources = json_decode($resources, true);
        }
        return $resources ? implode(', ', $resources) : 'Ninguno';
    }

    // Método para obtener recursos como array (para vistas)
    public function getResourcesArrayAttribute()
    {
        $resources = $this->resources;
        if (is_string($resources)) {
            return json_decode($resources, true) ?: [];
        }
        return $resources ?: [];
    }

    public function getFullLocationAttribute()
    {
        $location = $this->name;
        if ($this->building) {
            $location .= " - {$this->building->name}";
        }
        if ($this->floor) {
            $location .= " - Piso {$this->floor}";
        }
        if ($this->wing) {
            $location .= " - {$this->wing}";
        }
        return $location;
    }
}