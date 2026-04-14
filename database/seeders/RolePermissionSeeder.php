<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $worker = Role::where('name', 'worker')->first();

        $permissions = Permission::all();

        // ADMIN → ALL PERMISSIONS
        if ($admin) {
            $admin->permissions()->sync($permissions->pluck('id')->toArray());
        }

        // MANAGER → LIMITED
        if ($manager) {
            $manager->permissions()->sync(
                Permission::whereIn('group', ['projects', 'users'])
                    ->pluck('id')->toArray()
            );
        }

        // WORKER → BASIC ONLY
        if ($worker) {
            $worker->permissions()->sync(
                Permission::where('name', 'view-projects')
                    ->pluck('id')->toArray()
            );
        }
    }
}
