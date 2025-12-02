<?php

namespace App\Modules\GestionAcademica\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StudentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level', 
        'student_count',
        'special_features',
        'number_of_students',
        'special_requirements',
        'is_active',
        'academic_period_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'student_count' => 'integer',
        'number_of_students' => 'integer',
    ];
    
    public function requirements()
    {
        // Esta es una relación virtual para compatibilidad con el algoritmo
        return $this;
    }

    // AGREGAR ESTA RELACIÓN:
    public function assignments(): HasMany
    {
        return $this->hasMany(\App\Models\Assignment::class, 'student_group_id');
    }

    // Scope para HU4: Visualizar activos (auditoría soft-delete)
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Relación futura con período académico (nullable)
    public function academicPeriod()
    {
        return $this->belongsTo(\App\Models\AcademicPeriod::class);
    }

    // Método para factory
    protected static function newFactory()
    {
        return \Database\Factories\Modules\GestionAcademica\StudentGroupFactory::new();
    }
}