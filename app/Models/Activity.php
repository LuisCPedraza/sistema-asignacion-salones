<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ActivityGrade;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'title',
        'description',
        'due_date',
        'max_score',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'max_score' => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(\App\Modules\Asignacion\Models\Assignment::class);
    }

    public function grades()
    {
        return $this->hasMany(ActivityGrade::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
