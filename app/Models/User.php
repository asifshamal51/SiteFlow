<?php


namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

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
        'is_super_admin',
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
            'is_super_admin' => 'boolean',
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
//        return $this->belongsToMany(Role::class, 'user_roles');
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('project_id', 'is_active');
    }

    // 🔗 PROJECTS (future integration)
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users');
    }

    public function hasRole(string $role): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->roles()->where('name', $role)->exists();
    }

//    public function hasPermission(string $permission): bool
//    {
//        return $this->roles()
//            ->whereHas('permissions', function ($q) use ($permission) {
//                $q->where('name', $permission);
//            })->exists();
//    }

    public function hasPermission(string $permission): bool
    {
        // 🔥 SUPER ADMIN BYPASS
        if ($this->is_super_admin) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permission) {
                $q->where('name', $permission);
            })->exists();
    }
}
