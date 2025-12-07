<?php

namespace App\Modules\Asignacion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentRule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parameter', 'weight', 'is_active'];

    protected $casts = [
        'weight' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByWeight($query)
    {
        return $query->orderBy('weight', 'desc');
    }
}