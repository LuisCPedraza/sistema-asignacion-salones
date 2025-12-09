<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\AuditableModel;

class User extends Authenticatable
{
    use HasFactory, Notifiable, AuditableModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'temporary_access',
        'access_expires_at',
        'temporary_access_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'temporary_access' => 'boolean',
            'access_expires_at' => 'datetime',
            'temporary_access_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(\App\Modules\Auth\Models\Role::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($roleSlug)
    {
        // Si el usuario no tiene rol, devolver false
        if (!$this->role) {
            return false;
        }

        // Comparar el slug del rol
        return $this->role->slug === $roleSlug;
    }

    /**
     * Check if user can access the system.
     */
    public function canAccessSystem()
    {
        return $this->is_active && $this->role_id !== null;
    }

    /**
     * Check if user is pending approval.
     */
    public function isPendingApproval()
    {
        return !$this->is_active || $this->role_id === null;
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Check if temporary access has expired.
     */
    public function isTemporaryAccessExpired()
    {
        if (!$this->temporary_access_expires_at) {
            return false;
        }
        
        return now()->greaterThan($this->temporary_access_expires_at);
    }  
    
    public function teacher()
    {
        return $this->hasOne(\App\Modules\GestionAcademica\Models\Teacher::class);
    }
}