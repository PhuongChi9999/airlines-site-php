<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AirportsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('airports')->insert([
            [
                'name' => 'Paris Charles de Gaulle',
                'code' => 'CDG',
                'city' => 'Paris',
                'country' => 'France',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'New York JFK',
                'code' => 'JFK',
                'city' => 'New York',
                'country' => 'USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'London Heathrow',
                'code' => 'LHR',
                'city' => 'London',
                'country' => 'United Kingdom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tokyo Haneda',
                'code' => 'HND',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
