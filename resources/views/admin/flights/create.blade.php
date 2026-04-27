@extends('layout')
@section('content')

<h2 class="mb-4">✈️ Créer un vol</h2>

<form method="POST" action="{{ route('flights.store') }}">
    @csrf
    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif
    
    {{-- Infos générales --}}
    <div class="mb-3">
        <label class="form-label">Numéro de vol</label>
        <input type="text" name="flight_number" class="form-control" required>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">Aéroport de départ</label>
            <select name="departure_airport_id" class="form-select" required>
                @foreach($airports as $airport)
                    <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Aéroport d'arrivée</label>
            <select name="arrival_airport_id" class="form-select" required>
                @foreach($airports as $airport)
                    <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Date de départ</label>
            <input type="datetime-local" name="departure_date" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Date d'arrivée</label>
            <input type="datetime-local" name="arrival_date" class="form-control" required>
        </div>
    </div>

    {{-- Bloc classes en grille --}}
    <div class="row g-4 mb-4">
        @php
            $classes = [
                'E' => 'Classe Économique',
                'B' => 'Classe Affaires',
                'F' => 'Première Classe',
            ];
        @endphp

        @foreach ($classes as $key => $label)
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h6 class="mb-3">{{ $label }}</h6>
                    <div class="mb-2">
                        <label for="{{ $key }}_seats" class="form-label">Nombre de sièges</label>
                        <input type="number" name="{{ $key }}_seats" id="{{ $key }}_seats" class="form-control" min="0" required>
                    </div>
                    <div>
                        <label for="{{ $key }}_price" class="form-label">Prix par siège (€)</label>
                        <input type="number" name="{{ $key }}_price" id="{{ $key }}_price" class="form-control" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">💾 Créer le vol</button>
    </div>
</form>

@endsection
