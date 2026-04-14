<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
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

    public function show($id)
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
}
