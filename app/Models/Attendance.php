<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'fecha',
        'status',
        'comment',
        'taken_by',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function assignment()
    {
        return $this->belongsTo(\App\Modules\Asignacion\Models\Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function takenBy()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }
}
