<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // USERS
        Permission::firstOrCreate(['name' => 'create-users'], ['group' => 'users']);
        Permission::firstOrCreate(['name' => 'view-users'], ['group' => 'users']);
        Permission::firstOrCreate(['name' => 'edit-users'], ['group' => 'users']);
        Permission::firstOrCreate(['name' => 'delete-users'], ['group' => 'users']);

        // PROJECTS
        Permission::firstOrCreate(['name' => 'create-projects'], ['group' => 'projects']);
        Permission::firstOrCreate(['name' => 'view-projects'], ['group' => 'projects']);
        Permission::firstOrCreate(['name' => 'edit-projects'], ['group' => 'projects']);
        Permission::firstOrCreate(['name' => 'delete-projects'], ['group' => 'projects']);
    }
}
