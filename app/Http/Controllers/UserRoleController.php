<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);

        $user->roles()->syncWithoutDetaching([
            $request->role_id
        ]);

        return response()->json([
            'message' => 'Role assigned successfully'
        ]);
    }

    public function removeRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);

        if ($user->is_super_admin) {
            return response()->json([
                'message' => 'Cannot modify Super Admin'
            ], 403);
        }

        // 🔥 Prevent removing admin role from yourself (optional safety)
        if ($request->role_id == Role::where('name', 'admin')->value('id')
            && $request->user()->id == $user->id) {
            return response()->json([
                'message' => 'You cannot remove your own admin role'
            ], 403);
        }

        $user->roles()->detach($request->role_id);

        return response()->json([
            'message' => 'Role removed successfully'
        ]);
    }
}
