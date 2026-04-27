<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassengersSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le premier vol existant dans la table flights
        $flightId = DB::table('flights')->value('id');

        // S’il n’y a aucun vol, on ne crée pas de passagers
        if (!$flightId) {
            return;
        }

        DB::table('passengers')->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@gmail.com',
                // 'flight_id' => $flightId,
                'type' => 'adulte',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoe@gmail.com',
                // 'flight_id' => $flightId,
                'type' => 'enfant',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
