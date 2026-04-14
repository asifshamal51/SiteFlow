<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ], [
            'description' => 'System Administrator',
            'is_system' => true,
            'is_active' => true,
        ]);

        Role::firstOrCreate([
            'name' => 'manager'
        ], [
            'description' => 'Project Manager',
            'is_system' => true,
            'is_active' => true,
        ]);

        Role::firstOrCreate([
            'name' => 'worker'
        ], [
            'description' => 'Worker Role',
            'is_system' => true,
            'is_active' => true,
        ]);
    }
}
