<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableModel;

class Student extends Model
{
    use HasFactory, AuditableModel;

    protected $fillable = [
        'codigo',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'group_id',
        'estado',
        'observaciones',
    ];

    /**
     * Relación con el grupo al que pertenece
     */
    public function group()
    {
        return $this->belongsTo(\App\Modules\GestionAcademica\Models\StudentGroup::class, 'group_id');
    }

    /**
     * Obtener el nombre completo del estudiante
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Descripción para auditoría
     */
    public function getAuditableDescription(): string
    {
        return "Estudiante {$this->nombre_completo} ({$this->codigo})";
    }

    /**
     * Scope para estudiantes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para estudiantes de un grupo específico
     */
    public function scopeDeGrupo($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Models\StudentFactory::new();
    }
}
