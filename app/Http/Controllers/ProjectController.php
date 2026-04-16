<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:projects,code',
        ]);

        $project = Project::create([
            ...$request->all(),
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project
        ]);
    }

    public function index()
    {
        $projects = Project::latest()->get();

        return response()->json([
            'projects' => $projects
        ]);
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);

        return response()->json([
            'project' => $project
        ]);
    }

    public function users($projectId)
    {
        $project = Project::with([
            'users.roles.permissions'
        ])->findOrFail($projectId);

        return response()->json([
            'project' => $project->name,
            'users' => $project->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,

                    'project_role_id' => $user->pivot->role_id,

                    'roles' => $user->roles->map(function ($role) {
                        return [
                            'name' => $role->name,
                            'permissions' => $role->permissions->pluck('name')
                        ];
                    })
                ];
            })
        ]);
    }

    public function permissionMatrix($projectId)
    {
        $project = \App\Models\Project::with([
            'users' => function ($q) use ($projectId) {
                $q->with([
                    'roles' => function ($r) use ($projectId) {
                        $r->wherePivot('project_id', $projectId)
                            ->with('permissions:id,name');
                    }
                ]);
            }
        ])->findOrFail($projectId);

        $result = [
            'project_id' => $project->id,
            'project_name' => $project->name,
            'users' => []
        ];

        foreach ($project->users as $user) {
            $role = $user->roles->first();

            $result['users'][] = [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $role?->name,
                'permissions' => $role
                    ? $role->permissions->pluck('name')->values()
                    : []
            ];
        }

        return response()->json($result);
    }

    public function myProjects(Request $request)
    {
        $user = $request->user();

        $projects = $user->projects()
            ->select('projects.id', 'projects.name', 'projects.status')
            ->get();

        return response()->json([
            'projects' => $projects
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|nullable',
            'description' => 'sometimes|string|nullable',
            'type' => 'sometimes|string|nullable',

            'start_date' => 'sometimes|date|nullable',
            'end_date' => 'sometimes|date|nullable',

            'start_date_shamsi' => 'sometimes|string|nullable',
            'end_date_shamsi' => 'sometimes|string|nullable',

            'status' => 'sometimes|in:active,completed,on_hold',

            'currency_id' => 'sometimes|exists:currencies,id|nullable',
            'initial_budget' => 'sometimes|numeric|nullable',
            'progress_percent' => 'sometimes|numeric|min:0|max:100',
        ]);

        $project->update($request->only([
            'name',
            'location',
            'description',
            'type',
            'start_date',
            'end_date',
            'start_date_shamsi',
            'end_date_shamsi',
            'status',
            'currency_id',
            'initial_budget',
            'progress_percent',
        ]));

        return response()->json([
            'message' => 'Project updated successfully',
            'project' => $project
        ]);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        $project->delete(); // soft delete if enabled

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    public function restore($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $project->restore();

        return response()->json([
            'message' => 'Project restored successfully',
            'project' => $project
        ]);
    }
}
