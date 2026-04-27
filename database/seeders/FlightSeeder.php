<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách sân bay theo code
        $airportIds = DB::table('airports')->pluck('id', 'code');

        // Tổng số ghế mỗi chuyến
        $totalSeats = 180;

        // Giá theo hạng ghế
        $seatPrices = [
            'Classe Économique' => 950,
            'Classe Affaires' => 1500,
            'Première Classe' => 2500,
        ];

        // Tỷ lệ ghế
        $seatDistribution = [
            'Classe Économique' => ['ratio' => 0.6, 'code' => 'E'],
            'Classe Affaires' => ['ratio' => 0.2, 'code' => 'B'],
            'Première Classe' => ['ratio' => 0.2, 'code' => 'F']
        ];

        // Các hành trình giả định
        $routes = [
            ['from' => 'CDG', 'to' => 'JFK'],
            ['from' => 'JFK', 'to' => 'CDG'],
            ['from' => 'LHR', 'to' => 'HND'],
            ['from' => 'HND', 'to' => 'LHR'],
        ];

        for ($day = 1; $day <= 5; $day++) {
            $baseDate = Carbon::create(2025, 5, $day, 8);

            foreach ($routes as $i => $route) {
                $departure = (clone $baseDate)->addHours($i * 2);
                $arrival = (clone $departure)->addHours(8);
                $flightNumber = 'FL' . $route['from'] . $route['to'] . $day;

                // Tạo chuyến bay
                $flightId = DB::table('flights')->insertGetId([
                    'flight_number' => $flightNumber,
                    'departure_airport_id' => $airportIds[$route['from']],
                    'arrival_airport_id' => $airportIds[$route['to']],
                    'departure_date' => $departure,
                    'arrival_date' => $arrival,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Tạo ghế và tính tổng giá trị
                foreach ($seatDistribution as $class => $info) {
                    $seats = (int) ($totalSeats * $info['ratio']);
                    $price = $seatPrices[$class];

                    DB::table('flight_seats')->insert([
                        'flight_id' => $flightId,
                        'class' => $class,
                        'code' => $info['code'],
                        'total_seats' => $seats,
                        'available_seats' => $seats,
                        'price' => $price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                }

            }
        }
    }
}
