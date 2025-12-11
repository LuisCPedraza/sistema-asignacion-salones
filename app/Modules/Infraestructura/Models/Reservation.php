<?php

namespace App\Modules\Infraestructura\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'title',
        'description',
        'requester_name',
        'requester_email',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'aprobada');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rechazada');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelada');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now());
    }

    public function approve(): void
    {
        $this->update(['status' => 'aprobada']);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rechazada']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelada']);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Modules\Infraestructura\ReservationFactory::new();
    }
}
