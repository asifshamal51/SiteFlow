<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type',
        'is_active',
        'created_by',
    ];

    /**
     * Hidden fields for APIs
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // 🔗 RELATION: creator (self reference)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 🔗 ROLES (future integration)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // 🔗 PROJECTS (future integration)
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users');
    }
}
