<?php

namespace App\Modules\GestionAcademica\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableModel;

class Teacher extends Model
{
    use HasFactory, AuditableModel;

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
        'access_expires_at',
        'ip_address_allowed'
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
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Descripción para auditoría
     */
    public function getAuditableDescription(): string
    {
        return "Profesor {$this->full_name} ({$this->email})";
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

    protected static function newFactory()
    {
        return \Database\Factories\TeacherFactory::new();
    }

    // Relación con disponibilidades
    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    // Obtener disponibilidades activas
    public function getAvailableSlotsAttribute()
    {
        return $this->availabilities()->available()->get();
    }

    // Verificar disponibilidad en un día y hora específicos
    public function isAvailable($dayOfWeek, $time)
    {
        return $this->availabilities()
            ->available()
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->exists();
    }

    // Método para establecer disponibilidad semanal rápida
    public function setWeeklyAvailability($schedule)
    {
        // Eliminar disponibilidades existentes
        $this->availabilities()->delete();
        
        foreach ($schedule as $day => $slots) {
            foreach ($slots as $slot) {
                $this->availabilities()->create([
                    'day_of_week' => $day,
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    'is_available' => $slot['available'] ?? true,
                    'notes' => $slot['notes'] ?? null
                ]);
            }
        }
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