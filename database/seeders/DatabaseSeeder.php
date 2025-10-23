<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Manager 1',
            'username' => 'manager1',
            'password' => bcrypt('manager1'),
            'role' => 'manager',
            'department' => 'ppic',
        ]);

        \App\Models\User::create([
            'name' => 'Manager 2',
            'username' => 'manager2',
            'password' => bcrypt('manager2'),
            'role' => 'manager',
            'department' => 'production',
        ]);

        \App\Models\User::create([
            'name' => 'Staff 1',
            'username' => 'staff1',
            'password' => bcrypt('staff1'),
            'role' => 'staff',
            'department' => 'ppic',
        ]);

        \App\Models\User::create([
            'name' => 'Staff 2',
            'username' => 'staff2',
            'password' => bcrypt('staff2'),
            'role' => 'staff',
            'department' => 'production',
        ]);
    }
}
