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
        'is_available' => 'boolean'
    ];

    // Método para formatear start_time en la vista
    public function getFormattedStartTimeAttribute()
    {
        if ($this->start_time) {
            return is_string($this->start_time) 
                ? substr($this->start_time, 0, 5)  // "HH:MM" de string "HH:MM:SS"
                : Carbon::parse($this->start_time)->format('H:i');
        }
        return '-';
    }

    // Método para formatear end_time en la vista
    public function getFormattedEndTimeAttribute()
    {
        if ($this->end_time) {
            return is_string($this->end_time)
                ? substr($this->end_time, 0, 5)  // "HH:MM" de string "HH:MM:SS"
                : Carbon::parse($this->end_time)->format('H:i');
        }
        return '-';
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