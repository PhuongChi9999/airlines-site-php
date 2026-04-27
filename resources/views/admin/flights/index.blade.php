@extends('layout')

@section('content')
    <div class="container mt-4">
        <h2>🛫 Liste des vols</h2>

        <a href="{{ route('flights.create') }}" class="btn btn-success mb-3">➕ Créer un vol</a>

        @foreach($flights as $flight)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        ✈️ <strong>{{ $flight->flight_number }}</strong> :
                        {{ $flight->departureAirport->name }} → {{ $flight->arrivalAirport->name }}
                    </h5>

                    <p><strong>Départ :</strong> {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}</p>
                    <p><strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y H:i') }}</p>

                    <p><strong>Places disponibles :</strong></p>
                    <ul>
                        @foreach ($flight->seats as $seat)
                            @php
                                $seatClass = $seat->class; // Ex: 'Classe Économique'
                                $code = $seat->code;            // Ex: 'economy'
                                $totalSeats = $seat->total_seats;
                                $remainingSeats = $seat->available_seats;
                            @endphp

                            <li>
                                {{ ucfirst($seatClass) }} :
                                {{ $remainingSeats }} / {{ $totalSeats }} places —
                                Prix : {{ number_format($seat->price, 0, ',', ' ') }} €
                            </li>
                        @endforeach
                    </ul>

                    @php
                        // Compter tous les passagers sur ce vol
                        $passengerCount = DB::table('bookings')
                            ->join('booking_passenger', 'bookings.id', '=', 'booking_passenger.booking_id')
                            ->where('bookings.flight_id', $flight->id)
                            ->count();
                    @endphp

                    <p><strong>Réservés :</strong> {{ $passengerCount }} passagers</p>

                    {{-- Chỉ hiển thị cho admin --}}
                    @auth
                        @if(Auth::user()->is_admin)
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('flights.edit', $flight->id) }}" class="btn btn-primary btn-sm">
                                    ✏️ Modifier
                                </a>

                                <form action="{{ route('flights.destroy', $flight->id) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ce vol ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                </form>

                                <a href="{{ route('admin.flights.passengers', $flight->id) }}"
                                class="btn btn-sm btn-outline-info w-100">
                                    👥 Voir les passagers
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach

    </div>
@endsection
