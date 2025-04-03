<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
			DB::table('admins')->insert([
				[
						'username' => 'admin1',
						'password' => Hash::make('admin123'),
				],
				[
						'username' => 'admin2',
						'password' => Hash::make('admin123'),
				],
			]);
    }
}
