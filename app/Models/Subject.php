<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Career;
use App\Models\Semester;
use App\Models\CourseSchedule;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'specialty',
        'credit_hours',
        'lecture_hours',
        'lab_hours',
        'semester_level',
           'is_active',
           'career_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_hours' => 'integer',
        'lecture_hours' => 'integer',
        'lab_hours' => 'integer',
    ];

    // Relaciones
    public function courseSchedules()
    {
        return $this->hasMany(CourseSchedule::class);
    }

        public function career()
        {
            return $this->belongsTo(Career::class);
        }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'course_schedules');
    }

    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
