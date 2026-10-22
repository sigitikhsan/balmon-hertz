<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Super Admin
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@maganhertz.local',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'status' => 'aktif',
        ]);

        // Create Admin
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@maganhertz.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);
    }
}
