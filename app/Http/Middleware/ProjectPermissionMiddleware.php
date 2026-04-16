<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProjectPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();

        $projectId = $request->route('id'); // your routes use {id}

        if (!$projectId) {
            return response()->json([
                'message' => 'Project ID is required'
            ], 400);
        }

        if (!$user || !$user->hasProjectPermission($permission, $projectId)) {
            return response()->json([
                'message' => 'Forbidden. No permission in this project.'
            ], 403);
        }

        return $next($request);
    }
}
