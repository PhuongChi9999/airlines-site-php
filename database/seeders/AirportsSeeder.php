<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AirportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('airports')->insert([
					[
						'name' => 'Istanbul Airport',
						'city' => 'Istanbul',
						'country' => 'Turkey',
						'created_at' => now()->format('Y-m-d H:i:s'),
						'updated_at' => now()->format('Y-m-d H:i:s'),
					],
					[
						'name' => 'Dubai International Airport',
						'city' => 'Dubai',
						'country' => 'United Arab Emirates',
						'created_at' => now()->format('Y-m-d H:i:s'),
						'updated_at' => now()->format('Y-m-d H:i:s'),
					],
					[
						'name' => 'Paris Charles de Gaulle Airport',
						'city' => 'Paris',
						'country' => 'France',
						'created_at' => now()->format('Y-m-d H:i:s'),
						'updated_at' => now()->format('Y-m-d H:i:s'),
					],
					[
						'name' => 'Singapore Changi Airport',
						'city' => 'Singapore',
						'country' => 'Republic of Singapore',
						'created_at' => now()->format('Y-m-d H:i:s'),
						'updated_at' => now()->format('Y-m-d H:i:s'),
					]
				]);
    }
}
