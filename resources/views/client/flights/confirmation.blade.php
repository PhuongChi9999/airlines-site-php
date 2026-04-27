@extends('layout')

@section('title', 'Votre réservation vous attend')

@section('content')
    <div class="container mt-4">

        <div class="alert alert-success">
            Votre réservation a bien été ajoutée au panier.
        </div>

        {{-- Détails du vol --}}
        <div class="row mt-4">
        {{-- Vol aller --}}
        <div class="col-md-6">
            @php
                $classes = [
                    'E' => 'Classe Économique',
                    'B'   => 'Classe Affaires',
                    'F'   => 'Première Classe'
                ];
                $nbPassagers = $passengers->count();
                $totalGeneral = $totalPrice + $totalReturnPrice;
            @endphp

            <h5>🛫 Vol aller : {{ $flight->flight_number }}</h5>
            <p>
                De <strong>{{ $flight->departureAirport->name }}</strong>
                à <strong>{{ $flight->arrivalAirport->name }}</strong><br>
                Départ : {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}<br>
                Arrivée : {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y H:i') }}<br>
                Classe : <strong>{{ $classes[$departure_class] ?? 'Inconnue' }}</strong><br>
                Prix : <strong>{{ number_format($totalPrice, 0, ',', ' ') }} €</strong><br>
                Code de réservation : <strong>{{ $booking_id }}</strong>
            </p>
        </div>
        @if (isset($returnFlight))
        {{-- Vol retour --}}
        <div class="col-md-6">
            <h5>🛬 Vol retour : {{ $returnFlight->flight_number }}</h5>
            <p>
                De <strong>{{ $returnFlight->departureAirport->name }}</strong>
                à <strong>{{ $returnFlight->arrivalAirport->name }}</strong><br>
                Départ : {{ \Carbon\Carbon::parse($returnFlight->departure_date)->format('d/m/Y H:i') }}<br>
                Arrivée : {{ \Carbon\Carbon::parse($returnFlight->arrival_date)->format('d/m/Y H:i') }}<br>
                Classe : <strong>{{ $classes[$return_class] ?? 'Inconnue' }}</strong><br>
                Prix : <strong>{{ number_format($totalReturnPrice, 0, ',', ' ') }} €</strong><br>
                Code de réservation : <strong>{{ $return_booking_id }}</strong>
            </p>
        </div>
        @endif
    </div>

    {{-- Email du réservant --}}
    <div class="mb-4">
        <h5>👤 Réservé par :</h5>
        <p>{{ $email }}</p>
    </div>

    {{-- Liste des passagers --}}
    <div class="mb-4">
        <h5>👥 Passagers enregistrés :</h5>
        <ul class="list-group">
            @foreach($passengers as $p)
                <li class="list-group-item">
                    {{ $p->first_name }} {{ $p->last_name }}
                    <span class="text-muted">({{ ucfirst($p->type) }})</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-4">
        <h6 class="text-center">💶 Total : {{ number_format($totalGeneral, 0, ',', ' ') }} € ({{ $nbPassagers }} passagers)</h6>
    </div>

    <a href="{{ route('client.cart.index') }}" class="btn btn-outline-primary mt-3">🛒 Aller au panier</a>
</div>


@endsection
