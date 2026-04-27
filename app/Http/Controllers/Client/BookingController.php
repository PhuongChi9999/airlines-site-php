<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\FlightSeat;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function home()
    {
        $airports = Airport::all();
        return view('client.home', compact('airports'));
    }

    public function index(Request $request)
    {
        $airports = Airport::all();

        $query = Flight::query()->with(['departureAirport', 'arrivalAirport', 'seats']);

        if ($request->filled('departure_airport_id')) {
            $query->where('departure_airport_id', $request->departure_airport_id);
        }

        if ($request->filled('arrival_airport_id')) {
            $query->where('arrival_airport_id', $request->arrival_airport_id);
        }

        if ($request->filled('departure_date')) {
            $query->whereDate('departure_date', $request->departure_date);
        }

        $flights = $query->orderBy('departure_date', 'asc')->get();

        return view('client.flights.index', compact('airports', 'flights'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:9',
            'babies' => 'nullable|integer|min:0|max:9',
            'first_name' => 'required|array',
            'last_name' => 'required|array',
            'email' => 'required|email|max:255',
            'departure_flight_id' => 'required|exists:flights,id',
            'trip_type' => 'required|string|in:aller_simple,aller_retour',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date',
            'departure_class' => 'required|string',
        ]);

        $flight = Flight::findOrFail($request->departure_flight_id);
        $returnFlight = $request->return_flight_id ? Flight::with('seats')->find($request->return_flight_id) : null;

        $total = (int) $request->adults + (int) $request->children + (int) $request->babies;

        $types = array_merge(
            array_fill(0, $request->adults, 'adulte'),
            array_fill(0, $request->children ?? 0, 'enfant'),
            array_fill(0, $request->babies ?? 0, 'bébé')
        );
        
        $normalizedClass = $request->departure_class;
        $seat = $flight->seats()->where('code', $normalizedClass)->first();
        $pricePerAdult = $seat->price ?? 0;
        $pricePerChild = $pricePerAdult * 0.75;
        $pricePerBaby = 0;
        $totalPrice = ($request->adults * $pricePerAdult)
                    + ($request->children * $pricePerChild)
                    + ($request->babies * $pricePerBaby);

        $booking = Booking::create([
            'flight_id' => $flight->id,
            'status' => 'cart',
            'totalPrice' => $totalPrice,
        ]);
        
        for ($i = 0; $i < $total; $i++) {
            $passenger = Passenger::firstOrCreate([
                'first_name' => $request->first_name[$i],
                'last_name' => $request->last_name[$i],
                'email' => $request->email,
                //'email' => $i === 0 ? $request->email : null,
                'type' => $types[$i]
            ]);
        
            $booking->passengers()->attach($passenger->id, [
                'is_booker' => $i === 0,
                'seat_class' => $normalizedClass
            ]);
        }

        $cart = session()->get('cart', []);
        $cart[] = $booking->id;

        $returnTotalPrice = 0;
        if(isset($returnFlight)) {

            $seat = $returnFlight->seats()->where('code', $request->return_class)->first();
            $pricePerAdult = $seat->price ?? 0;
            $pricePerChild = $pricePerAdult * 0.75;
            $pricePerBaby = 0;
            $returnTotalPrice = ($request->adults * $pricePerAdult)
                    + ($request->children * $pricePerChild)
                    + ($request->babies * $pricePerBaby);

            $returnBooking = Booking::create([
                'flight_id' => $returnFlight->id,
                'status' => 'cart',
                'totalPrice' => $returnTotalPrice,
            ]);

            for ($i = 0; $i < $total; $i++) {
                $passenger = Passenger::firstOrCreate([
                    'first_name' => $request->first_name[$i],
                    'last_name' => $request->last_name[$i],
                    'email' => $request->email,
                    //'email' => $i === 0 ? $request->email : null,
                    'type' => $types[$i]
                ]);
            
                $returnBooking->passengers()->attach($passenger->id, [
                    'is_booker' => $i === 0,
                    'seat_class' => $request->return_class
                ]);
            }

            $cart[] = $returnBooking->id;
        }

        session(['cart' => $cart]);

        // Récupérer la ligne FlightSeat correspondant à la classe choisie
        $seat = $flight->seats()->where('code', $normalizedClass)->first();

        return view('client.flights.confirmation', [
            'flight' => $flight,
            'returnFlight' => $returnFlight,
            'email' => $request->email,
            'passengers' => $booking->passengers,
            'unitPrice' => $pricePerAdult,
            'totalPrice' => $totalPrice,
            'totalReturnPrice' => $returnTotalPrice,
            'departure_class' => $normalizedClass,
            'return_class' => $request->return_class,
            'booking_id' => $booking->id,
            'return_booking_id' => isset($returnBooking) ? $returnBooking->id:null
        ]);
        
    }

    public function confirmation()
    {
        return view('client.flights.confirmation');
    }

    public function recherche(Request $request)
    {
        $request->validate([
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id',
            'departure_date' => 'required|date',
            'trip_type' => 'required|in:aller_simple,aller_retour',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'selected_class' => 'required|string',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'babies' => 'nullable|integer|min:0',
        ]);

        $class = $request->selected_class;
        $reservedCount = $request->adults + $request->children;
        // Vols aller
        $departureFlights = Flight::where('departure_airport_id', $request->departure_airport_id)
        ->where('arrival_airport_id', $request->arrival_airport_id)
        ->whereDate('departure_date', $request->departure_date)
        ->whereHas('seats', function ($q) use ($class, $reservedCount) {
            $q->where('code', $class)
                ->where('available_seats', '>=', $reservedCount);
        })
        ->get();

        // Vols retour (si aller-retour demandé)
        $returnFlights = collect(); // vide par défaut
        if ($request->trip_type === 'aller_retour' && $request->filled('return_date')) {
            $returnFlights = Flight::where('departure_airport_id', $request->arrival_airport_id)
                ->where('arrival_airport_id', $request->departure_airport_id)
                ->whereDate('departure_date', $request->return_date)
                ->whereHas('seats', function ($q) use ($class, $reservedCount) {
                    $q->where('code', $class)
                    ->where('available_seats', '>=', $reservedCount);
                })
                ->get();
        }

        return view('client.flights.resultats', [
            'flights' => $departureFlights,
            'returnFlights' => $returnFlights,
            'adults' => $request->adults,
            'children' => $request->children,
            'babies' => $request->babies,
            'trip_type' => $request->trip_type,
            'departure_class' => $class,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date
        ]);
    }

    public function fillForm(Request $request)
    {
        $departureFlight = Flight::with('seats')->find($request->departure_flight_id);
        $returnFlight = $request->return_flight_id ? Flight::with('seats')->find($request->return_flight_id) : null;

        $seat = $departureFlight->seats()->where('code', $request->departure_class)->first();
        $returnSeat = $request->return_flight_id ? $returnFlight->seats()->where('code', $request->return_class)->first() : null;

        $requestedSeats = $request->adults + $request->children + $request->babies;
        if ($seat->available_seats < $requestedSeats) {
            return redirect()->back()->with('error', 'Vol ' . $departureFlight->flight_number . ' : il ne reste que ' . $seat->available_seats . ' place(s) disponible(s) en ' . $seat->class . '.');
        }

        if (isset($returnSeat) && $returnSeat->available_seats < $requestedSeats) {
            return redirect()->back()->with('error', 'Vol ' . $returnFlight->flight_number . ' : il ne reste que ' 
            . $returnSeat->available_seats . ' place(s) disponible(s) en ' . $returnSeat->class . '.');
        }

        $pricePerAdult = $seat->price ?? 0;
        $pricePerChild = $pricePerAdult * 0.75;
        $pricePerBaby = 0;
        $totalPrice = ($request->adults * $pricePerAdult)
                    + ($request->children * $pricePerChild)
                    + ($request->babies * $pricePerBaby);
                    
        $returnTotalPrice = 0;
        if(isset($returnFlight)) {
            $returnPricePerAdult = $returnSeat->price ?? 0;
            $returnPricePerChild = $pricePerAdult * 0.75;
            $returnPricePerBaby = 0;
            $returnTotalPrice = ($request->adults * $returnPricePerAdult)
                    + ($request->children * $returnPricePerChild)
                    + ($request->babies * $returnPricePerBaby);
        }
        return view('client.flights.fillForm', [
            'flight' => $departureFlight,
            'returnFlight' => $returnFlight,
            'seat' => $seat,
            'departure_class' => $request->departure_class,
            'return_class' => $request->return_class,
            'trip_type' => $request->trip_type,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date,
            'adults' => $request->adults,
            'children' => $request->children,
            'babies' => $request->babies,
            'totalPrice' => $totalPrice,
            'totalReturnPrice' => $returnTotalPrice
        ]);
    }

    public function checkForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $email = $user->email;
            $bookings = Booking::with(['passengers'])
            ->where('status', 'confirmée')
            ->whereHas('passengers', function ($query) use ($email) {
                $query->where('email', $email);
            })
            ->orderByDesc('id')
            ->get();

            return view('client.booking.result', compact('bookings'));
        } else {        // Non connecté → formulaire simple
            logger('Utilisateur non connecté — retour formulaire');
            return view('client.booking.check');
        }
    }

    public function check(Request $request)
    {
        $request->validate([
            'booking_id' => 'nullable|integer',
            'email' => 'nullable|email',
        ]);

        if ($request->filled('booking_id')) {
            $booking = Booking::with(['passengers', 'flight'])->find($request->booking_id);
    
            if (!$booking) {
                return redirect()->back()->with('error', 'Aucune réservation trouvée pour cet ID.');
            }
    
            return view('client.booking.result', compact('booking'));
        }
    
        if ($request->filled('email')) {
            $email = $request->input('email');
    
            $bookings = Booking::with(['flight.seats', 'passengers'])
                ->whereHas('passengers', function ($query) use ($email) {
                    $query->where('email', $email);
                })
                ->orderByDesc('id')
                ->get();
    
            if ($bookings->isEmpty()) {
                return redirect()->back()->with('error', 'Aucune réservation trouvée pour cette adresse e-mail.');
            }
    
            return view('client.booking.result', compact('bookings', 'email'));
        }
    
        return redirect()->back()->with('error', 'Veuillez renseigner un identifiant ou un email.');
    
    }

}
