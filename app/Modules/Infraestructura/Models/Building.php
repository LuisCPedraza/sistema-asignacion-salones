<?php

namespace App\Modules\Infraestructura\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'floors',
        'description',
        'is_active',
        'facilities'
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_active' => 'boolean'
    ];

    // Relación con salones
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    // Scope para edificios activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Métodos de utilidad
    public function getFacilitiesListAttribute()
    {
        return $this->facilities ? implode(', ', $this->facilities) : 'Ninguna';
    }

    public function getActiveClassroomsCountAttribute()
    {
        return $this->classrooms()->active()->count();
    }

    public function getTotalCapacityAttribute()
    {
        return $this->classrooms()->active()->sum('capacity');
    }
}