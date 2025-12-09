<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'name',
        'day',
        'start_time',
        'end_time',
        'shift',
        'schedule_type', // 'day' = Diurno, 'night' = Nocturno
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
        'duration_minutes' => 'integer',
    ];

    // Ámbito para slots diurnos (8:00-18:00)
    public function scopeDay($query)
    {
        return $query->where('schedule_type', 'day');
    }

    // Ámbito para slots nocturnos (18:00-22:00)
    public function scopeNight($query)
    {
        return $query->where('schedule_type', 'night');
    }
}
