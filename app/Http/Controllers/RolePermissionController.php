<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function assignPermissions(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($roleId);

        $role->permissions()->sync($request->permissions);

        return response()->json([
            'message' => 'Permissions assigned to role successfully'
        ]);
    }

    public function removePermission(Request $request, $roleId)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findOrFail($roleId);

        $role->permissions()->detach($request->permission_id);

        return response()->json([
            'message' => 'Permission removed from role successfully'
        ]);
    }
}
