<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PassengersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('passengers')->insert([
				[
					'username' => 'johndoe',
					'password' => Hash::make('password123'),
					'first_name' => 'John',
					'last_name' => 'Doe',
					'email' => 'johndoe@gmail.com',
					'flight_history' => json_encode(['AF123', 'AF456', 'AF789'])
			],
			]);
    }
}
