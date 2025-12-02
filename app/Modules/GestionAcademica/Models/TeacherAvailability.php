<?php

namespace App\Modules\GestionAcademica\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TeacherAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'day',
        'start_time', 
        'end_time',
        'is_available',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_available' => 'boolean'
    ];

    // Método accessor para formatear start_time
    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
    }

    // Método accessor para formatear end_time  
    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
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
        
        return $days[$this->day] ?? $this->day;
    }

    // Método para formatear horario
    public function getTimeRangeAttribute()
    {
        return $this->start_time . ' - ' . $this->end_time;
    }
}