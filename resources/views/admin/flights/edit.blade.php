@extends('layout')
@section('content')

<h2>Modifier un vol</h2>

<form method="POST" action="{{ route('flights.update', $flight) }}">
    @csrf @method('PUT')

    <div class="mb-3">
        <label for="flight_number" class="form-label">Numéro de vol</label>
        <input type="text" name="flight_number" value="{{ $flight->flight_number }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="departure_airport_id" class="form-label">Aéroport de départ</label>
        <select name="departure_airport_id" class="form-select" required>
            @foreach($airports as $airport)
                <option value="{{ $airport->id }}" @selected($airport->id == $flight->departure_airport_id)>
                    {{ $airport->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="arrival_airport_id" class="form-label">Aéroport d'arrivée</label>
        <select name="arrival_airport_id" class="form-select" required>
            @foreach($airports as $airport)
                <option value="{{ $airport->id }}" @selected($airport->id == $flight->arrival_airport_id)>
                    {{ $airport->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="departure_date" class="form-label">Date de départ</label>
        <input type="datetime-local" name="departure_date" value="{{ $flight->departure_date }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="arrival_date" class="form-label">Date d'arrivée</label>
        <input type="datetime-local" name="arrival_date" value="{{ $flight->arrival_date }}" class="form-control" required>
    </div>

    {{-- Bloc classes en grille --}}
    <div class="row g-4 mb-4">
    @php
        $values = [];
        foreach ($flight->seats ?? [] as $seat) {
            $values[$seat->code] = [
                'seats' => $seat->total_seats,
                'price' => $seat->price,
                'label' => $seat->class
            ];
        }
    @endphp


        @foreach ($values as $code => $info)
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h6 class="mb-3">{{ $info['label'] }}</h6>
                    <div class="mb-2">
                        <label for="{{ $code }}_seats" class="form-label">Total nombre de sièges</label>
                        <input type="number" name="{{ $code }}_seats" id="{{ $code }}_seats" class="form-control" min="0" value="{{ $info['seats'] ?? '' }}">
                    </div>
                    <div>
                        <label for="{{ $code }}_price" class="form-label">Prix par siège (€)</label>
                        <input type="number" name="{{ $code }}_price" id="{{ $code }}_price" class="form-control" min="0" step="0.01" value="{{ $info['price'] ?? '' }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-primary w-50">
        💾 Mettre à jour
    </button>

    <a href="{{ route('flights.index') }}" class="btn btn-outline-secondary w-50">
        ❌ Annuler
    </a>
</div>

</form>

@endsection
