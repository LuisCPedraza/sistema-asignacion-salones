<?php

namespace App\Modules\AcademicManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'student_count',
        'special_characteristics',
        'is_active',
        'academic_period_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'student_count' => 'integer',
    ];

    public function academicPeriod()
    {
        return $this->belongsTo(\App\Models\AcademicPeriod::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}