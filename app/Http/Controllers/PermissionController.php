<?php

namespace App\Http\Controllers;

use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('is_active', true)
            ->select('id', 'name', 'group')
            ->orderBy('group')
            ->get()
            ->groupBy('group');

        return response()->json([
            'permissions' => $permissions
        ]);
    }
}
