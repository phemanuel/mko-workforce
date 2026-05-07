<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    

    public function run(): void
    {
        // ✅ CREATE ROLES FIRST
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        // 👑 ADMIN
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@mko.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'status' => 'approved'
        ]);

        // 🧑 SUPERVISOR
        User::create([
            'name' => 'Supervisor One',
            'email' => 'supervisor@mko.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole->id,
            'status' => 'approved'
        ]);

        // 👷 STAFF
        User::create([
            'name' => 'Staff One',
            'email' => 'staff@mko.com',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
            'status' => 'approved'
        ]);
    }
}
