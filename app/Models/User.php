<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'temporary_access_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'temporary_access_expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(\App\Modules\Auth\Models\Role::class);
    }

    public function hasRole($roleSlug)
    {
        return $this->role && $this->role->slug === $roleSlug;
    }

    public function isTemporaryAccessExpired()
    {
        if (!$this->temporary_access_expires_at) {
            return false;
        }
        return now()->greaterThan($this->temporary_access_expires_at);
    }

    public function canAccessSystem()
    {
        return $this->is_active && !$this->isTemporaryAccessExpired();
    }

    // Scope para usuarios activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
