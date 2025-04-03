<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('flights')->insert([
					[
					'flight_number' => 'AF123',
					'departure_airport_id' => 1,
					'arrival_airport_id' => 2,
					'departure_date' => '2025-05-01 10:00:00',
					'arrival_date' => '2025-05-01 12:00:00',
					'available_seats' => 100,
					'price' => 150.00,
				],
				[
					'flight_number' => 'AF456',
					'departure_airport_id' => 2,
					'arrival_airport_id' => 3,
					'departure_date' => '2025-05-02 14:00:00',
					'arrival_date' => '2025-05-02 16:00:00',
					'available_seats' => 80,
					'price' => 200.00,
				],
				[
					'flight_number' => 'AF789',
					'departure_airport_id' => 3,
					'arrival_airport_id' => 1,
					'departure_date' => '2025-05-26 14:00:00',
					'arrival_date' => '2025-05-26 16:00:00',
					'available_seats' => 60,
					'price' => 100.00,
				],
				[
					'flight_number' => 'ZK789',
					'departure_airport_id' => 3,
					'arrival_airport_id' => 4,
					'departure_date' => '2025-05-03 15:00:00',
					'arrival_date' => '2025-05-03 16:00:00',
					'available_seats' => 74,
					'price' => 178.90,
				],
				[
					'flight_number' => 'ZK123',
					'departure_airport_id' => 4,
					'arrival_airport_id' => 1,
					'departure_date' => '2025-05-06 13:50:00',
					'arrival_date' => '2025-05-06 16:00:00',
					'available_seats' => 49,
					'price' => 149.99,
				],
			]);
    }
}
