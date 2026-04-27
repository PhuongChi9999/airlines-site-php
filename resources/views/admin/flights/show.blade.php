@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>👥 Passagers du vol {{ $flight->flight_number }}</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            ✈️ Informations du vol
        </div>
        <div class="card-body">
            <p><strong>De :</strong> {{ $flight->departureAirport->name }}</p>
            <p><strong>Vers :</strong> {{ $flight->arrivalAirport->name }}</p>
            <p><strong>Date de départ :</strong> {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}</p>
            <p><strong>Date d'arrivée :</strong> {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y H:i') }}</p>

            {{-- Tính số ghế còn trống và tổng giá tiền --}}
            @php
                $classMap = [
                    'Classe Économique' => 'economy',
                    'Classe Affaires' => 'business',
                    'Première Classe' => 'first',
                ];

                $seatCounts = [];
                $classPrices = [];

                foreach ($flight->seats as $seat) {
                    $techClass = $classMap[$seat->class] ?? $seat->class;
                    $reserved = $flight->passengers->where('seat_class', $techClass)->count();
                    $remaining = $seat->available_seats - $reserved;
                    $seatCounts[] = $remaining;

                    $classPrices[$techClass] = $seat->price;
                }

                $availableSeats = array_sum($seatCounts);

                $totalPrice = 0;
                foreach ($flight->passengers as $passenger) {
                    $totalPrice += $classPrices[$passenger->seat_class] ?? 0;
                }
            @endphp

            <p><strong>Places disponibles :</strong> {{ $availableSeats }}</p>
            <p><strong>Prix :</strong> {{ number_format($totalPrice, 0, ',', ' ') }} €</p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
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
            @foreach ($flight->passengers as $passenger)
                <tr>
                    <td>{{ $passenger->first_name }}</td>
                    <td>{{ $passenger->last_name }}</td>
                    <td>{{ $passenger->email ?? '—' }}</td>
                    <td>{{ ucfirst($passenger->type) }}</td>
                    <td>{{ $passenger->seat_class }}</td>
                    <td>{!! $passenger->is_booker ? '✅' : '❌' !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary mt-3">⬅️ Retour à la liste des vols</a>
</div>
@endsection
