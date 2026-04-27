@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">👥 Passagers du vol {{ $flight->flight_number }}</h2>

    {{-- Détails du vol --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            ✈️ Informations du vol
        </div>
        <div class="card-body">
            <p><strong>De :</strong> {{ $flight->departureAirport->name }}</p>
            <p><strong>Vers :</strong> {{ $flight->arrivalAirport->name }}</p>
            <p><strong>Date de départ :</strong> {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}</p>
            <p><strong>Date d'arrivée :</strong> {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y H:i') }}</p>
            <p><strong>Places disponibles :</strong> {{ $flight->available_seats }}</p>
            <ul>
                @php
                    $classes = [];
                @endphp
                @foreach ($flight->seats as $seat)
                
                    @php
                        $classes[$seat->code] = $seat -> class;
                        $totalSeats = $seat->total_seats;
                        $remainingSeats = $seat->available_seats;
                    @endphp
                
                <li>
                        {{ ucfirst($seat->class) }} :
                        {{ $remainingSeats }} / {{ $totalSeats }} places —
                        Prix : {{ number_format($seat->price, 0, ',', ' ') }} €
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Liste des passagers --}}
    @if ($flight->bookings->count() > 0)
        <div class="table-responsive">
        @php
            $allPassengers = collect();

            foreach ($flight->bookings as $booking) {
                $allPassengers = $allPassengers->merge($booking->passengers);
            }

            $nbAdults = $allPassengers->where('type', 'adulte')->count();
            $nbChildren = $allPassengers->where('type', 'enfant')->count();
            $nbBabies = $allPassengers->where('type', 'bébé')->count();
            $total = $allPassengers->count();
        @endphp


        <div class="alert alert-info mt-3">
            👥 <strong>{{ $total }}</strong> passager(s) au total pour ce vol :
            {{ $nbAdults }} adulte(s),
            {{ $nbChildren }} enfant(s),
            {{ $nbBabies }} bébé(s)
        </div>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Classe</th>
                        <th>Réservant ?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flight->bookings->sortBy('id') as $booking)
                        <tr class="table-secondary fw-bold">
                            <td colspan="6">
                                🧾 Réservation #{{ $booking->id }} —
                                {{ $booking->passengers->count() }} passager(s)
                            </td>
                        </tr>

                        @foreach($booking->passengers as $passenger)
                            <tr>
                                <td>{{ $passenger->first_name }}</td>
                                <td>{{ $passenger->last_name }}</td>
                                <td>{{ $passenger->email ?? '—' }}</td>
                                <td>{{ ucfirst($passenger->type) }}</td>
                                <td>{{ $passenger->pivot->seat_class ?? '—' }}</td>
                                <td>
                                    {{ $passenger->pivot->is_booker ? '✅' : '❌' }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning">
            Aucun passager n'a encore réservé ce vol.
        </div>
    @endif


    <a href="{{ route('flights.index') }}" class="btn btn-secondary mt-4">⬅️ Retour à la liste des vols</a>
</div>
@endsection
