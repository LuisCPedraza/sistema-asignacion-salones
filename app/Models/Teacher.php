<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'user_id'
    ];

    protected $casts = [
        'specialties' => 'array',
        'weekly_availability' => 'array',
        'is_active' => 'boolean',
        'years_experience' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getSpecialtiesListAttribute()
    {
        return $this->specialties ? implode(', ', $this->specialties) : 'Ninguna';
    }
}