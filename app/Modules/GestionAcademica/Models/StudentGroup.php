<?php

namespace App\Modules\GestionAcademica\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\AuditableModel;

class StudentGroup extends Model
{
    use HasFactory, AuditableModel;

    protected $fillable = [
        'name',
        'level', 
        'student_count',
        'special_features',
        'number_of_students',
        'special_requirements',
        'is_active',
        'academic_period_id',
        'semester_id',
        'group_type',    // A = Diurno, B = Nocturno
        'schedule_type', // day = Diurno (8:00-18:00), night = Nocturno (18:00-22:00)
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
    public function assignments()
    {
        return $this->hasMany(\App\Modules\Asignacion\Models\Assignment::class, 'student_group_id');
    }

    // Nueva relación: Pertenece a un semestre
    public function semester()
    {
        return $this->belongsTo(\App\Models\Semester::class);
    }

    // Nueva relación: Estudiantes del grupo
    public function students()
    {
        return $this->hasMany(\App\Models\Student::class, 'group_id');
    }

    // Nueva relación: A través del semestre, pertenece a una carrera
    public function career()
    {
        return $this->hasOneThrough(
            \App\Models\Career::class,
            \App\Models\Semester::class,
            'id',
            'id',
            'semester_id',
            'career_id'
        );
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