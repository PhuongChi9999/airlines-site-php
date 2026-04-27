<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
								'surname' => 'admin',
                'email' => 'admin@site.com',
                'password' => bcrypt('admin123'),
								'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
						],
						[
							'name' => 'user',
							'surname' => 'user',
							'email' => 'user@site.com',
							'password' => bcrypt('user123'),
							'is_admin' => false,
							'created_at' => now(),
							'updated_at' => now(),
					]
        ]);
    }
}
