<?php

namespace App\Modules\Asignacion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\TimeSlot;
use App\Models\Activity;
use App\Traits\AuditableModel;

class Assignment extends Model
{
    use HasFactory, AuditableModel;

    protected $table = 'assignments';

    protected $fillable = [
        'student_group_id',
        'teacher_id',
        'classroom_id',
        'subject_id',
        'time_slot_id',
        'day',
        'start_time',
        'end_time',
        'score',
        'assigned_by_algorithm',
        'is_confirmed',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time'   => 'datetime:H:i:s',
        'score'      => 'decimal:2',
        'assigned_by_algorithm' => 'boolean',
        'is_confirmed' => 'boolean',
    ];

    // RELACIONES ESTÁNDAR Y CLARAS (Laravel las ama así)
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

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }

    // Scopes útiles
    public function scopeConfirmed($query)
    {
        return $query->where('is_confirmed', true);
    }

    /**
     * Descripción para auditoría
     */
    public function getAuditableDescription(): string
    {
        $subjectName = $this->subject->name ?? 'Materia';
        $groupName = $this->group->name ?? 'Grupo';
        $teacherName = $this->teacher->full_name ?? 'Profesor';
        
        return "Asignación: {$subjectName} - {$groupName} (Prof. {$teacherName})";
    }

    public function scopePending($query)
    {
        return $query->where('is_confirmed', false);
    }

    public function scopeForDay($query, $day)
    {
        return $query->where('day', $day);
    }
}