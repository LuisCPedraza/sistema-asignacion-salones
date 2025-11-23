<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    public const ADMINISTRADOR = 'administrador';
    public const SECRETARIA_ADMINISTRATIVA = 'secretaria_administrativa';
    public const COORDINADOR = 'coordinador';
    public const SECRETARIA_COORDINACION = 'secretaria_coordinacion';
    public const COORDINADOR_INFRAESTRUCTURA = 'coordinador_infraestructura';
    public const SECRETARIA_INFRAESTRUCTURA = 'secretaria_infraestructura';
    public const PROFESOR = 'profesor';
    public const PROFESOR_INVITADO = 'profesor_invitado';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getRoles()
    {
        return [
            self::ADMINISTRADOR => 'Administrador',
            self::SECRETARIA_ADMINISTRATIVA => 'Secretaria Administrativa',
            self::COORDINADOR => 'Coordinador',
            self::SECRETARIA_COORDINACION => 'Secretaria de Coordinación',
            self::COORDINADOR_INFRAESTRUCTURA => 'Coordinador de Infraestructura',
            self::SECRETARIA_INFRAESTRUCTURA => 'Secretaria de Infraestructura',
            self::PROFESOR => 'Profesor',
            self::PROFESOR_INVITADO => 'Profesor Invitado',
        ];
    }
}