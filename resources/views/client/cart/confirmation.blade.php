@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>✅ Réservation confirmée</h3>
    <p class="text-muted">Merci pour votre réservation. Voici les détails :</p>

    @foreach ($bookings as $booking)
        <div class="card mb-4">
            <div class="card-header">
                🧾 Réservation #{{ $booking->id }}
            </div>
            <div class="card-body">
                {{-- Vols --}}
                    @php
                        $seatClass = $booking->passengers->first()?->pivot->seat_class ?? null;
                        $seat = $booking->flight->seats->firstWhere('code', $seatClass);
                    @endphp

                    <h5 class="mb-1">
                        ✈️ Vol {{ $booking->flight->flight_number }} :
                        {{ $booking->flight->departureAirport->name }} → {{ $booking->flight->arrivalAirport->name }}
                    </h5>
                    <p>
                        Départ : {{ \Carbon\Carbon::parse($booking->flight->departure_date)->format('d/m/Y H:i') }}<br>
                        Arrivée : {{ \Carbon\Carbon::parse($booking->flight->arrival_date)->format('d/m/Y H:i') }}<br>
                        Classe : <strong>{{ $seat->class ?? strtoupper($seatClass) }}</strong><br>
                        Prix unitaire : {{ number_format($seat->price ?? 0, 0, ',', ' ') }} €
                    </p>
                    <hr>

                {{-- Passagers --}}
                <h6>👥 Passagers :</h6>
                <ul class="list-group list-group-flush">
                    @foreach ($booking->passengers as $passenger)
                        <li class="list-group-item">
                            {{ $passenger->first_name }} {{ $passenger->last_name }}
                            <span class="text-muted">({{ ucfirst($passenger->type) }})</span>
                            @if ($passenger->pivot->is_booker)
                                <span class="badge bg-info ms-2">Réservant</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <div class="text-end mt-3">
                    💶 <strong>Total :</strong> {{ number_format($booking->totalPrice, 0, ',', ' ') }} €
                </div>
            </div>
        </div>
    @endforeach
    <div class="alert alert-success text-end">
        💳 <strong>Prix total du panier :</strong> {{ number_format($totalCartPrice, 0, ',', ' ') }} €
    </div>

    <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            🏠 Retour à l'accueil
        </a>
        <a href="{{ route('booking.checkForm') }}" class="btn btn-outline-secondary">
            🔍 Voir une autre réservation
        </a>
    </div>
</div>
@endsection
