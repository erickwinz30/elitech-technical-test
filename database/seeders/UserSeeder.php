<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Create PPIC Manager
		User::create([
			'name' => 'Manager PPIC',
			'username' => 'ppic_manager',
			'password' => Hash::make('password'),
			'role' => 'manager',
			'department' => 'ppic',
		]);

		// Create PPIC Staff
		User::create([
			'name' => 'Staff PPIC',
			'username' => 'ppic_staff',
			'password' => Hash::make('password'),
			'role' => 'staff',
			'department' => 'ppic',
		]);

		// Create Production Manager
		User::create([
			'name' => 'Manager Production',
			'username' => 'production_manager',
			'password' => Hash::make('password'),
			'role' => 'manager',
			'department' => 'production',
		]);

		// Create Production Staff
		User::create([
			'name' => 'Staff Production',
			'username' => 'production_staff',
			'password' => Hash::make('password'),
			'role' => 'staff',
			'department' => 'production',
		]);
	}
}
