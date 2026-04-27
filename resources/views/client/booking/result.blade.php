@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>📄 Détails de réservation(s)</h3>

    {{-- Si une seule réservation est envoyée --}}
    @isset($booking)
        @php $bookings = collect([$booking]); @endphp
    @endisset

@if ($bookings->isEmpty())
    <div class="alert alert-warning">
        ❌ Aucune réservation trouvée.
    </div>
@else
    {{-- Si plusieurs (ou une convertie) --}}
    @foreach ($bookings as $booking)
        <div class="card mb-4">
            <div class="card-header">
                🧾 Réservation #{{ $booking->id }} — {{ $booking->created_at->format('d/m/Y') }}
            </div>
            <div class="card-body">
                @php $flight = $booking->flight; @endphp

                <h5>
                    ✈️ {{ $flight->flight_number }} —
                    {{ $flight->departureAirport->name }} → {{ $flight->arrivalAirport->name }}
                </h5>
                <p>
                    Départ : {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}<br>
                    Arrivée : {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y H:i') }}
                </p>
                <hr>

                {{-- Passagers --}}
                <h6>👥 Passagers :</h6>
                <ul class="list-group list-group-flush">
                    @foreach ($booking->passengers as $p)
                        <li class="list-group-item">
                            {{ $p->first_name }} {{ $p->last_name }}
                            <span class="text-muted">({{ ucfirst($p->type) }})</span>
                            @if ($p->pivot->is_booker)
                                <span class="badge bg-info ms-2">Réservant</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <p class="text-end mt-3">
                    💶 <strong>Total :</strong> {{ number_format($booking->totalPrice, 0, ',', ' ') }} €
                </p>
            </div>
        </div>
    @endforeach

    <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('booking.checkForm') }}" class="btn btn-outline-primary">
            🔍 Nouvelle recherche
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            🏠 Retour à l'accueil
        </a>
    </div>
@endif
</div>
@endsection
