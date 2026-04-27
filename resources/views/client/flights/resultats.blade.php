@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Résultats de la recherche :</h3>

    <p>
        {{ $adults }} adulte(s),
        {{ $children ?? 0 }} enfant(s),
        {{ $babies ?? 0 }} bébé(s)<br>
        Type de voyage : {{ $trip_type == 'aller_retour' ? 'Aller-retour' : 'Aller simple' }}<br>
        Date aller : {{ \Carbon\Carbon::parse($departure_date)->format('d/m/Y') }}<br>
        @if($return_date)
            Date retour : {{ \Carbon\Carbon::parse($return_date)->format('d/m/Y') }}
        @endif
    </p>

    @if($flights->count() > 0)
    <div class="container mt-4">
    <h4>Choisissez votre vol</h4>

    <form method="GET" action="{{ route('flights.fillForm') }}">
        @csrf

        <div class="row row-cols-1 row-cols-md-2 g-3">
            {{-- Colonne ALLER --}}
            <div class="col">
                <h5>🛫 Vols aller</h5>
                @foreach ($flights as $vol)
                    <div class="card mb-3" id="{{ $vol->flight_number }}">
                        <div class="card-body">
                            <h6 class="card-title">{{ $vol->flight_number }}</h6>
                            <p class="card-text">
                                De {{ $vol->departureAirport->name }} à {{ $vol->arrivalAirport->name }}<br>
                                Départ : {{ \Carbon\Carbon::parse($vol->departure_date)->format('d/m/Y H:i') }}<br>
                                Arrivée : {{ \Carbon\Carbon::parse($vol->arrival_date)->format('d/m/Y H:i') }}<br>
                            </p>

                            {{-- Liste des classes et places disponibles --}}    
                                <ul>
                                @foreach ($vol->seats as $seat)
                                    @php
                                        $class = $seat->class;
                                        $price = $seat->price;
                                        $totalSeats = $seat->total_seats;
                                        $remainingSeats = $seat->available_seats;
                                    @endphp

                                    <li>
                                        {{ ucfirst($class) }} :
                                        {{ $remainingSeats }} / {{ $totalSeats }} places —
                                        Prix : {{ number_format($price, 0, ',', ' ') }} €
                                    </li>
                                @endforeach
                            </ul>


                            <button type="button"
                                    class="btn btn-outline-primary btn-sm w-100"
                                    onclick="selectFlight('departure', {{ $vol->id }}, '{{ $vol->flight_number }}')">
                                Choisir ce vol
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($returnFlights->count() >0)
            {{-- Colonne RETOUR --}}
            <div class="col">
                <h5>🛬 Vols retour</h5>
                @foreach ($returnFlights as $vol)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">{{ $vol->flight_number }}</h6>
                            <p class="card-text">
                                De {{ $vol->departureAirport->name }} à {{ $vol->arrivalAirport->name }}<br>
                                Départ : {{ \Carbon\Carbon::parse($vol->departure_date)->format('d/m/Y H:i') }}<br>
                                Arrivée : {{ \Carbon\Carbon::parse($vol->arrival_date)->format('d/m/Y H:i') }}<br>
                            </p>

                            {{-- Liste des classes et places disponibles --}}    
                                <ul>
                                @foreach ($vol->seats as $seat)
                                    @php
                                        $class = $seat->class;
                                        $price = $seat->price;
                                        $totalSeats = $seat->total_seats;
                                        $remainingSeats = $seat->available_seats;
                                    @endphp

                                    <li>
                                        {{ ucfirst($class) }} :
                                        {{ $remainingSeats }} / {{ $totalSeats }} places —
                                        Prix : {{ number_format($price, 0, ',', ' ') }} €
                                    </li>
                                @endforeach
                            </ul>

                            <button type="button"
                                    class="btn btn-outline-primary btn-sm w-100"
                                    onclick="selectFlight('return', {{ $vol->id }}, '{{ $vol->flight_number }}')">
                                Choisir ce vol
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Résumé + bouton de validation --}}
        <div class="mt-4">
            <div id="selected-flights" class="mb-2"></div>

            <input type="hidden" name="departure_flight_id" id="departure_flight_id">
            <input type="hidden" name="return_flight_id" id="return_flight_id">
            <input type="hidden" name="departure_class" value="{{ $departure_class }}">
            <input type="hidden" name="return_class" value="{{ $departure_class }}">
            <input type="hidden" name="trip_type" value="{{ $trip_type }}">
            <input type="hidden" name="adults" value="{{ $adults }}">
            <input type="hidden" name="children" value="{{ $children }}">
            <input type="hidden" name="babies" value="{{ $babies }}">
            <input type="hidden" name="departure_date" value="{{ $departure_date }}">
            <input type="hidden" name="return_date" value="{{ $return_date }}">

            

                    <button type="submit" class="btn btn-success" disabled id="validateBtn">
                        ✅ Continuer vers la réservation
                    </button>

                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        🔙 Chercher nouveau vol
                    </a>
        </div>
    </form>
</div>
@else
        <div class="alert alert-warning">
        Aucun vol trouvé
        </div>
@endif

{{-- Script de sélection --}}
<script>
    let selectedDeparture = null;
    let selectedReturn = null;

    function selectFlight(type, id, label) {
        if (type === 'departure') {
            document.getElementById('departure_flight_id').value = id;
            selectedDeparture = label;
        } else {
            document.getElementById('return_flight_id').value = id;
            selectedReturn = label;
        }

        let summary = '';
        if (selectedDeparture) {
            summary += '🛫 Vol aller : ' + selectedDeparture + '<br>';
        }
        if (selectedReturn) {
            summary += '🛬 Vol retour : ' + selectedReturn;
        }

        document.getElementById('selected-flights').innerHTML = summary;
        document.getElementById('validateBtn').disabled = (!selectedDeparture && !selectedReturn);
    }
</script>



</div>
@endsection
