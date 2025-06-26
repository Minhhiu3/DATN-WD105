<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';

    protected $fillable = [
        'name',
        'account_name',
        'email',
        'phone_number',
        'password',
        'role_id',
        'status',
        'email_verified_at',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id_role');
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'Admin';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === true;
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get user's display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->account_name ?: $this->name;
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', 'Admin');
        });
    }

    /**
     * Scope for regular users
     */
    public function scopeRegularUsers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', 'User');
        });
    }
}
