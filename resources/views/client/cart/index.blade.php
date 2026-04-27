@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">🛒 Mon panier de réservations</h2>

    @if ($bookings->isEmpty())
        <div class="alert alert-info">Votre panier est vide.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Vol</th>
                        <th>Classe</th>
                        <th>Passagers</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        @php
                            $flight = $booking->flight;
                            $class = $booking->passengers->first()?->pivot->seat_class ?? 'Inconnue';
                            $seat=$flight->seats->firstWhere('code', $class);
                            $total = $booking->totalPrice;														
                        @endphp
                        <tr>
                            <td>
                                ✈️ {{ $flight->flight_number }}<br>
                                <small>{{ $flight->departureAirport->code }} → {{ $flight->arrivalAirport->code }}</small><br>
                                <small><small>{{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}
                                →
                                {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y H:i') }}</small></small>
                            </td>
                            <td>{{ $seat?->code }}</td>
                            <td>
                                {{ $booking->passengers->count() }}
                                
                            </td>
                            <td>{{ number_format($total, 0, ',', ' ') }} €</td>
                            <td class="text-center">
                                <a href="{{ route('cart.edit', $booking->id) }}" class="btn btn-sm btn-outline-secondary">
                                    ✏️ Modifier</a>
                                <form method="POST" action="{{ route('client.cart.remove', $booking->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Retirer ce vol du panier ?')">
                                        ❌ Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <div class="alert alert-info text-end mt-4">
                        💳 <strong>Total du panier :</strong> {{ number_format($totalCartPrice, 0, ',', ' ') }} €
                    </div>

                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('client.cart.confirm') }}" class="text-end mt-4">
            @csrf
            <button class="btn btn-success">
                ✅ Confirmer toutes les réservations
            </button>
        </form>
    @endif
</div>
@endsection
