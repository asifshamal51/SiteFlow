<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

//        if (!$user || (!$user->is_super_admin && !$user->hasRole('admin'))) {
//            return response()->json([
//                'message' => 'Forbidden. Admin access only.'
//            ], 403);
//        }
//
//        return $next($request);

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

// 🔥 Super Admin bypass
        if ($user->is_super_admin) {
            return $next($request);
        }

// 🔥 Normal admin check
        if (!$user->hasRole('admin')) {
            return response()->json([
                'message' => 'Forbidden. Admin access only.'
            ], 403);
        }

        return $next($request);
    }
}
