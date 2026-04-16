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

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users')
            ->withPivot('role_id', 'assigned_by', 'is_active')
            ->withTimestamps();
    }

    public function projectRoles($projectId)
    {
        return $this->belongsToMany(Role::class, 'project_users')
            ->withPivot('project_id', 'is_active')
            ->wherePivot('project_id', $projectId);
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

    public function hasProjectPermission(string $permission, int $projectId): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        // 1. Get role(s) assigned to user in THIS project
        $roleIds = \DB::table('project_users')
            ->where('user_id', $this->id)
            ->where('project_id', $projectId)
            ->where('is_active', 1)
            ->pluck('role_id');

        if ($roleIds->isEmpty()) {
            return false;
        }

        // 2. Check permissions via role_permissions
        return \DB::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->whereIn('role_permissions.role_id', $roleIds)
            ->where('permissions.name', $permission)
            ->exists();
    }
}
