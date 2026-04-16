<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    public function assignUser(Request $request, $projectId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $project = Project::findOrFail($projectId);

        $project->users()->syncWithoutDetaching([
            $request->user_id => [
                'role_id' => $request->role_id,
                'assigned_by' => $request->user()->id,
                'is_active' => true,
            ]
        ]);

        return response()->json([
            'message' => 'User assigned to project successfully'
        ]);
    }

    public function removeUser(Request $request, $projectId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($projectId);

        $project->users()->detach($request->user_id);

        return response()->json([
            'message' => 'User removed from project'
        ]);
    }
}
