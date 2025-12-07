<?php

namespace App\Modules\Infraestructura\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'day',
        'start_time',
        'end_time',
        'is_available',
        'notes',
        'availability_type'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_available' => 'boolean'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
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
        return $this->start_time->format('H:i:s') . ' - ' . $this->end_time->format('H:i:s');
    }

    // Método para formatear tipo de disponibilidad
    public function getAvailabilityTypeNameAttribute()
    {
        $types = [
            'regular' => 'Regular',
            'maintenance' => 'Mantenimiento',
            'reserved' => 'Reservado',
            'special_event' => 'Evento Especial'
        ];
        
        return $types[$this->availability_type] ?? $this->availability_type;
    }
}