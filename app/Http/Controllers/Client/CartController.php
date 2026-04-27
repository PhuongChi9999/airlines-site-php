<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Passenger;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        $bookingIds = session('cart', []);

				$bookings = Booking::with([
					'flight.departureAirport',
					'flight.arrivalAirport',
					'flight.seats',
					'passengers'
				])->whereIn('id', $bookingIds)->get();

        $totalCartPrice = $bookings->sum('totalPrice');
				
        return view('client.cart.index', compact('bookings','totalCartPrice'));
    }

    public function confirm()
    {
        $bookingIds = session('cart', []);
        $bookings = Booking::with(['flight.seats','passengers'])->whereIn('id', $bookingIds)->get();

        foreach ($bookings as $booking) {
            $nbPassagers = $booking->passengers->count();

						$firstPassenger = $booking->passengers->first();
						$seatClass = $firstPassenger?->pivot->seat_class ?? null;
						
						if (!$seatClass) continue;
						
            $seat = $booking->flight->seats()->where('code', $seatClass)->first();
            if ($seat) {
                $seat->available_seats = max(0, $seat->available_seats - $nbPassagers);
                $seat->save();
            }

            $booking->status = 'confirmée';
            $booking->save();
        }

        session()->forget('cart');
        $totalCartPrice = $bookings->sum('totalPrice');
        return view('client.cart.confirmation', compact('bookings', 'totalCartPrice'));
    }

    
    public function remove(Booking $booking)
    {
        $cart = session()->get('cart', []);
        $cart = array_filter($cart, fn($id) => $id != $booking->id);
        session(['cart' => array_values($cart)]); // réindexer proprement
    
        $booking->delete(); // ou status = annulé
    
        return back()->with('success', 'Réservation retirée du panier.');
    }

    public function edit(Booking $booking)
    {
        $booking->load(['passengers', 'flight']);

        return view('client.cart.edit', [
            'booking' => $booking,
            'flight' => $booking->flight,
            'selected_class' => $booking->passengers->first()?->pivot->seat_class ?? null,
           ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $booking->load('passengers', 'flight');

        $seatClass = $request->input('seat_class');

        // 1. Supprimer les passagers cochés
        $toRemove = $request->input('remove_existing', []);
        if (!empty($toRemove)) {
            foreach ($toRemove as $passengerId) {
                $booking->passengers()->detach($passengerId);
                Passenger::find($passengerId)?->delete();
            }
        }

        // 2. Mettre à jour les passagers restants
        $existingIds = $request->input('existing_ids', []);
        $firstNames = $request->input('existing_first_name', []);
        $lastNames = $request->input('existing_last_name', []);
        $types = $request->input('existing_type', []);

        foreach ($existingIds as $index => $id) {
            if (in_array($id, $toRemove)) continue; // ne pas mettre à jour si supprimé

            $passenger = Passenger::find($id);
            if ($passenger) {
                $passenger->first_name = $firstNames[$index];
                $passenger->last_name = $lastNames[$index];
                $passenger->type = $types[$index];
                $passenger->save();

                // mise à jour pivot
                $booking->passengers()->updateExistingPivot($passenger->id, [
                    'seat_class' => $seatClass
                ]);
            }
        }

        // 3. Ajouter les nouveaux passagers
        $newFirstNames = $request->input('new_first_name', []);
        $newLastNames = $request->input('new_last_name', []);
        $newTypes = $request->input('new_type', []);

        foreach ($newFirstNames as $i => $prenom) {
            $newPassenger = Passenger::create([
                'first_name' => $prenom,
                'last_name' => $newLastNames[$i],
                'type' => $newTypes[$i],
            ]);

            $booking->passengers()->attach($newPassenger->id, [
                'seat_class' => $seatClass,
                'is_booker' => false
            ]);
        }

        $booking->status = 'cart';
        $booking->save();

        $booking->load('passengers', 'flight');
        // 4. Recalcul du prix total
        $nbAdults = $booking->passengers->where('type', 'adulte')->count();
        $nbChildren = $booking->passengers->where('type', 'enfant')->count();
        $nbBabies = $booking->passengers->where('type', 'bébé')->count();

        // Trouver le siège
        $seat = $booking->flight->seats()->where('code', $seatClass)->first();

        $pricePerAdult = $seat->price ?? 0;
        $pricePerChild = $pricePerAdult * 0.75;
        $pricePerBaby = 0;

        $totalPrice = ($nbAdults * $pricePerAdult)
                    + ($nbChildren * $pricePerChild)
                    + ($nbBabies * $pricePerBaby);

        $booking->totalPrice = $totalPrice;
        $booking->save();

        return redirect()->route('client.cart.index')->with('success', 'Réservation modifiée avec succès.');
    }


}
