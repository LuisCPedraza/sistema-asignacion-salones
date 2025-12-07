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
        'is_active',
        'academic_period_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
}