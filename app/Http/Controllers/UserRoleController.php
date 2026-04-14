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
}
