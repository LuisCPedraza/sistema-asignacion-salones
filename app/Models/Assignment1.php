<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_group_id',
        'teacher_id',
        'classroom_id',
        'day',
        'start_time',
        'end_time',
        'score',
        'notes',
        'is_confirmed',
        'duration',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'score' => 'decimal:2',
        'is_confirmed' => 'boolean'
    ];

    // Relaciones
    public function group()
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Scope para horarios por usuario (HU14)
    public function scopeByUser($query, $user)
    {
        if ($user->hasRole('profesor')) {
            $query->whereHas('teacher', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query;
    }
}