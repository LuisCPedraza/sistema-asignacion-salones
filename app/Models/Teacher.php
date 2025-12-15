<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSchedule;
use App\Modules\Asignacion\Models\Assignment;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialty',
        'specialties',
        'curriculum',
        'years_experience',
        'academic_degree',
        'is_active',
        'availability_notes',
        'weekly_availability',
        'special_assignments',
        'user_id',
        'is_guest',
        'access_expires_at'
    ];

    protected $casts = [
        'specialties' => 'array',
        'weekly_availability' => 'array',
        'is_active' => 'boolean',
        'years_experience' => 'integer',
        'is_guest' => 'boolean',
        'access_expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(\App\Modules\GestionAcademica\Models\TeacherAvailability::class);
    }

    public function courseSchedules()
    {
        return $this->hasMany(CourseSchedule::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGuest($query)
    {
        return $query->where('is_guest', true);
    }

    public function scopeWithValidAccess($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('access_expires_at')
              ->orWhere('access_expires_at', '>', now());
        });
    }

    public function scopeExpiredGuest($query)
    {
        return $query->where('is_guest', true)
                     ->where('access_expires_at', '<=', now());
    }

    public function getSpecialtiesListAttribute()
    {
        return $this->specialties ? implode(', ', $this->specialties) : 'Ninguna';
    }

    public function isAccessValid(): bool
    {
        if (!$this->is_guest) {
            return true;
        }

        if (!$this->access_expires_at) {
            return true;
        }

        return now() <= $this->access_expires_at;
    }
}