<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 🔐 LOGIN API
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'User is inactive'
            ], 403);
        }

        // 🔐 CREATE TOKEN
        $token = $user->createToken('siteflow-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,

            // 👇 USER DATA SENT IN LOGIN RESPONSE
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'phone' => $user->phone,
            ]
        ]);
    }

    // 👤 PROFILE API
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    // 🚪 LOGOUT API
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
