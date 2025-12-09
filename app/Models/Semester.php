<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id',
        'number',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'number' => 'integer',
    ];

    // Relaciones
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function studentGroups()
    {
        return $this->hasMany(\App\Modules\GestionAcademica\Models\StudentGroup::class);
    }

    public function courseSchedules()
    {
        return $this->hasMany(CourseSchedule::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_schedules');
    }
}
