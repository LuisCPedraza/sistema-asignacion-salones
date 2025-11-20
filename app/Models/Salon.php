<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;  // Para UUID

class Salon extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salones';  // Tabla 'salones' (español, alinea con migración)

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;  // False para UUID string (no int auto-increment)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo',
        'capacidad',
        'ubicacion',
        'recursos',  // JSON for horarios and other resources
        'activo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'recursos' => 'array',  // JSON to array for horarios/resources
        'activo' => 'boolean',
    ];

    /**
     * Boot method for UUID generation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();  // Genera UUID para id
            }
        });
    }

    /**
     * Relación con asignaciones (uno a muchos).
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class);
    }

    /**
     * Get horarios from recursos JSON.
     *
     * @return array
     */
    public function getHorariosAttribute()
    {
        return $this->recursos['horarios'] ?? [];
    }

    /**
     * Set horarios in recursos JSON.
     *
     * @param array $horarios
     */
    public function setHorariosAttribute($horarios)
    {
        $recursos = $this->recursos ?? [];  // Get current recursos or empty
        $recursos['horarios'] = $horarios;  // Set horarios
        $this->attributes['recursos'] = json_encode($recursos);  // Set as JSON attribute
    }

    /**
     * Scope para salones activos.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
