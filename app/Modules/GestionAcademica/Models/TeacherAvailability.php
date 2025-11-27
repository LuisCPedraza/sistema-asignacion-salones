<?php

namespace App\Modules\GestionAcademica\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'day_of_week',
        'start_time', 
        'end_time',
        'is_available',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_available' => 'boolean'
    ];

    // Método accessor para formatear start_time
    public function getStartTimeAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    // Método accessor para formatear end_time  
    public function getEndTimeAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    // Relación con profesor
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Scope para disponibilidades activas
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Método para formatear día en español
    public function getDayNameAttribute()
    {
        $days = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado'
        ];
        
        return $days[$this->day_of_week] ?? $this->day_of_week;
    }

    // Método para formatear horario
    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }
}