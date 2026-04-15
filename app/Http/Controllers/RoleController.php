<?php

namespace App\Http\Controllers;

use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('is_active', true)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'roles' => $roles
        ]);
    }

    public function withPermissions()
    {
        $roles = Role::with('permissions:id,name')
            ->where('is_active', true)
            ->get();

        return response()->json([
            'roles' => $roles
        ]);
    }
}
