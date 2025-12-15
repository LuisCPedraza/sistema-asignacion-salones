<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Career extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'duration_semesters',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_semesters' => 'integer',
    ];

    // Relaciones
    public function semesters()
    {
        return $this->hasMany(Semester::class)->orderBy('number');
    }

    public function studentGroups()
    {
        return $this->hasManyThrough(
            \App\Modules\GestionAcademica\Models\StudentGroup::class,
            Semester::class,
            'career_id',
            'semester_id'
        );
    }
}
