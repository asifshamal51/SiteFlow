<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Prevent duplicate admin creation
        if (User::where('email', 'admin@siteflow.com')->exists()) {
            return;
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@siteflow.com',
            'password' => Hash::make('admin123'), // CHANGE IN PRODUCTION
            'is_active' => true,
            'user_type' => 'admin',
        ]);
    }
}
