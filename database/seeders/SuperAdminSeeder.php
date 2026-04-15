<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('email', 'superadmin@siteflow.com')->exists()) {
            return;
        }

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@siteflow.com',
            'password' => Hash::make('super123'), // CHANGE IN PRODUCTION
            'is_super_admin' => true,
            'is_active' => true,
        ]);
    }
}
