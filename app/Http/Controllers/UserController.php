<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $authUser = $request->user();

        if (!$authUser) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type ?? 'user',
            'phone' => $request->phone,
            'created_by' => $authUser->id,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function index()
    {
        $users = \App\Models\User::with('roles.permissions')->get();

        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,

                'roles' => $user->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,

                        'permissions' => $role->permissions->pluck('name')
                    ];
                })
            ];
        });

        return response()->json([
            'users' => $data
        ]);
    }

    public function userRolePermission($id)
    {
        $user = \App\Models\User::with('roles.permissions')->findOrFail($id);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(function ($role) {
                    return [
                        'name' => $role->name,
                        'permissions' => $role->permissions->pluck('name')
                    ];
                })
            ]
        ]);
    }

    public function getAllUsers()
    {
        $users = User::with([
            'roles:id,name',
            'roles.permissions:id,name'
        ])
            ->select('id', 'name', 'email', 'is_active', 'is_super_admin')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                    'is_super_admin' => $user->is_super_admin,

                    'roles' => $user->roles->pluck('name'),

                    'permissions' => $user->roles
                        ->flatMap->permissions
                        ->pluck('name')
                        ->unique()
                        ->values(),
                ];
            });

        return response()->json([
            'users' => $users
        ]);
    }

    public function deleteUser(Request $request, $id)
    {
        $authUser = $request->user();

        $user = User::findOrFail($id);

        // 🔥 Prevent deleting yourself
        if ($authUser->id === $user->id) {
            return response()->json([
                'message' => 'You cannot delete yourself'
            ], 403);
        }

        // 🔥 Protect Super Admin
        if ($user->is_super_admin) {
            return response()->json([
                'message' => 'Cannot delete Super Admin'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user->trashed()) {
            return response()->json([
                'message' => 'User is not deleted'
            ], 400);
        }

        $user->restore();

        return response()->json([
            'message' => 'User restored successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $authUser = $request->user();

        $user = User::findOrFail($id);

        // 🔥 Prevent updating super admin (optional safety)
        if ($user->is_super_admin && !$authUser->is_super_admin) {
            return response()->json([
                'message' => 'Only Super Admin can update this user'
            ], 403);
        }

        if ($authUser->id === $user->id && $request->is_active === false) {
            return response()->json([
                'message' => 'You cannot deactivate yourself'
            ], 403);
        }

        // 🔥 Validation
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        // 🔥 Update fields
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->phone = $request->phone ?? $user->phone;

        // 🔐 Password update (only if provided)
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // 🔥 Active status (admin control)
        if (!is_null($request->is_active)) {
            $user->is_active = $request->is_active;
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::with([
            'roles:id,name',
            'roles.permissions:id,name'
        ])->findOrFail($id);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'roles' => $user->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions' => $role->permissions->pluck('name')
                    ];
                })
            ]
        ]);
    }

    public function deletedUsers()
    {
        $users = User::onlyTrashed()
            ->select('id', 'name', 'email', 'deleted_at')
            ->with('roles:id,name')
            ->get();
        return response()->json([
            'deleted_users' => $users
        ]);
    }
}
