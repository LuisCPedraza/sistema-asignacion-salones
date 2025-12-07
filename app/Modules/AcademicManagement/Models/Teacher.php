<?php

namespace App\Modules\AcademicManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialties',
        'resume_url',
        'is_active',
        'max_hours_per_week'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'specialties' => 'array',
        'max_hours_per_week' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}