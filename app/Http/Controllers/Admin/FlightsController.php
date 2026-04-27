<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\FlightSeat;
use Exception;
use Illuminate\Http\Request;


class FlightsController extends Controller
{
    public function index()
    {
        $flights = Flight::with(['departureAirport', 'arrivalAirport', 'seats'])
        ->orderBy('departure_date', 'asc')    
        ->orderBy('arrival_date', 'asc')       
        ->get();
        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        $airports = Airport::all();
        return view('admin.flights.create', compact('airports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flight_number' => 'required|unique:flights',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date'
        ]);

        $seatData = [
            'Classe Économique' => [
                'count' => $request->input('E_seats'),
                'price' => $request->input('E_price'),
                'code' => 'E'
            ],
            'Classe Affaires' => [
                'count' => $request->input('B_seats'),
                'price' => $request->input('B_price'),
                'code' => 'B'
            ],
            'Première Classe' => [
                'count' => $request->input('F_seats'),
                'price' => $request->input('F_price'),
                'code' => 'F'
            ],
        ];

        $flight = Flight::create($request->all());

        foreach ($seatData as $className => $info) {
            FlightSeat::create([
                'flight_id' => $flight->id,
                'class' => $className,
                'total_seats' => $info['count'],
                'available_seats' => $info['count'],
                'price' => $info['price'],
                'code' => $info['code']
            ]);
        }
        return redirect()->route('flights.index')->with('success', 'Vol créé');
    }

    public function edit(Flight $flight)
    {
        $airports = Airport::all();
        return view('admin.flights.edit', compact('flight', 'airports'));
    }

    public function update(Request $request, Flight $flight)
    {
        $seatData = [
            'E' => [
                'count' => $request->input('E_seats'),
                'price' => $request->input('E_price'),
            ],
            'B' => [
                'count' => $request->input('B_seats'),
                'price' => $request->input('B_price'),
            ],
            'F' => [
                'count' => $request->input('F_seats'),
                'price' => $request->input('F_price'),
            ],
        ];

        $flight->update($request->all());

        foreach ($seatData as $code => $data) {
            $seat = $flight->seats->firstWhere('code', $code);
    
            if ($seat) {
                // Mise à jour des valeurs existantes
                $delta = $data['count'] - $seat->total_seats;
                $seat->total_seats = $data['count'];
                $seat->available_seats+=$delta;
                $seat->price = $data['price'];
                $seat->save();
            }
        }
        return redirect()->route('flights.index')->with('success', 'Vol modifié');
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();
        return redirect()->route('flights.index')->with('success', 'Vol supprimé');
    }

    public function showPassengers($id)
    {
//        $flight = Flight::with(['departureAirport', 'arrivalAirport'])->findOrFail($id);
        $flight = Flight::with('bookings.passengers')->findOrFail($id);
        return view('admin.flights.passengers', compact('flight'));
    }


}
