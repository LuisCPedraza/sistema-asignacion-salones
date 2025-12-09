<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'semester_id',
        'position_in_semester',
        'required_teachers',
    ];

    protected $casts = [
        'position_in_semester' => 'integer',
        'required_teachers' => 'integer',
    ];

    // Relaciones
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
