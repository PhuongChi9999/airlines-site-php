@extends('layouts.app')

@section('content')
<div class="container mt-4">

@if (session('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif

@php
    $user = auth()->check() ? auth()->user() : null;
@endphp

    <div class="card">
        <div class="card-header bg-primary text-white">
            ✈️ Réserver un vol
        </div>
        <div class="card-body">

            {{-- Sélection de la classe --}}
            <form method="GET" action="{{ route('flights.fillForm') }}" class="row g-3 mb-4">
                <input type="hidden" name="departure_class" value="{{ $departure_class }}">
                <input type="hidden" name="return_class" value="{{ $return_class }}"> 
                <input type="hidden" name="departure_flight_id" value="{{ $flight->id }}">
                <input type="hidden" name="trip_type" value="{{ $trip_type }}">
                <input type="hidden" name="departure_date" value="{{ $departure_date }}">
                <input type="hidden" name="return_date" value="{{ $return_date }}">
                <input type="hidden" name="adults" value="{{ $adults }}">
                <input type="hidden" name="children" value="{{ $children }}">
                <input type="hidden" name="babies" value="{{ $babies }}">

                <div class="row mt-4">
                    {{-- Vol aller --}}
                    <div class="col-md-6">
                        <h5>🛫 Vol aller : {{ $flight->flight_number }}</h5>
                        <p>
                            {{ $flight->departureAirport->name }} → {{ $flight->arrivalAirport->name }}<br>
                            Départ : {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}<br>
                            Arrivée : {{ \Carbon\Carbon::parse($flight->arrival_date)->format('d/m/Y H:i') }}<br>
                        </p>
                        <div class="mb-3">
                            <select name="departure_class" class="form-select" onchange="this.form.submit()">
                                @foreach ($flight->seats as $option)
                                    <option value={{ $option->code }} {{ (isset($departure_class) && $option->code === $departure_class) ? 'selected' : '' }}>
                                        {{ $option->class }} — {{ number_format($option->price, 0, ',', ' ') }} € ({{ $option->available_seats }} places)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Détail sélectionné --}}
                        @php
                            // Récupérer le FlightSeat correspondant à cette classe
                            $seat = $flight->seats->firstWhere('code', $departure_class);
                        @endphp

                        @if ($seat)
                            <ul>
                                <li>Adulte(s) : {{ $adults }}</li>
                                <li>Enfant(s) : {{ $children }}</li>
                                <li>Bébé(s) : {{ $babies }}</li>
                                <li><strong>Prix total : </strong><strong>{{ number_format($totalPrice, 0, ',', ' ') }} €</strong></li>
                                
                            </ul>
                        @else
                            <p><em>Classe non reconnue ou aucune place disponible.</em></p>
                        @endif
                    </div>

                    @if (isset($returnFlight))
                    {{-- Vol retour --}}
                    <div class="col-md-6">
                        <h5>🛬 Vol retour : {{ $returnFlight->flight_number }}</h5>
                        <p>
                            {{ $returnFlight->departureAirport->name }} → {{ $returnFlight->arrivalAirport->name }}<br>
                            Départ : {{ \Carbon\Carbon::parse($returnFlight->departure_date)->format('d/m/Y H:i') }}<br>
                            Arrivée : {{ \Carbon\Carbon::parse($returnFlight->arrival_date)->format('d/m/Y H:i') }}<br>
                        </p>
                        <div class="mb-3">
                            <select name="return_class" class="form-select" onchange="this.form.submit()">
                                @foreach ($returnFlight->seats as $option)
                                <option value={{ $option->code }} {{ (isset($return_class) && $option->code === $return_class) ? 'selected' : '' }}>
                                        {{ $option->class }} — {{ number_format($option->price, 0, ',', ' ') }} € ({{ $option->available_seats }} places)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="return_flight_id" value="{{ $returnFlight->id }}">
                        {{-- Détail sélectionné --}}
                        @php
                            // Récupérer le FlightSeat correspondant à cette classe
                            $returnSeat = $returnFlight->seats->firstWhere('code', $return_class);
                        @endphp

                        @if ($returnSeat)
                            <ul>
                                <li>Adulte(s) : {{ $adults }}</li>
                                <li>Enfant(s) : {{ $children }}</li>
                                <li>Bébé(s) : {{ $babies }}</li>
                                <li><strong>Prix total : </strong><strong>{{ number_format($totalReturnPrice, 0, ',', ' ') }} €</strong></li>
                                
                            </ul>
                        @else
                            <p><em>Classe non reconnue ou aucune place disponible.</em></p>
                        @endif
                    </div>
                    @endif
                </div>
                
            </form>

            {{-- Erreurs de validation --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulaire de réservation --}}
            <form method="POST" action="{{ route('flights.book') }}">
                @csrf

                <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                <input type="hidden" name="trip_type" value="{{ old('trip_type', $trip_type) }}">
                <input type="hidden" name="departure_date" value="{{ old('departure_date', $departure_date) }}">
                <input type="hidden" name="return_date" value="{{ old('return_date', $return_date) }}">
                <input type="hidden" name="departure_class" value="{{ old('departure_class', $departure_class) }}">
                <input type="hidden" name="return_class" value="{{ old('return_class', $return_class) }}">
                <input type="hidden" name="adults" value="{{ old('adults', $adults) }}">
                <input type="hidden" name="children" value="{{ old('children', $children) }}">
                <input type="hidden" name="babies" value="{{ old('babies', $babies) }}">
                <input type="hidden" name="departure_flight_id" value="{{ $flight->id }}">
                <input type="hidden" name="return_flight_id" value="{{ isset($returnFlight) ? $returnFlight->id : '' }}">

                {{-- Responsable --}}
                <div class="border p-3 mb-3">
                    <h5>Adulte 1 (Responsable)</h5>
                    <div class="mb-2">
                        <label>Prénom</label>
                        <input type="text" name="first_name[]" class="form-control" 
                        value="{{ old('first_name.0', $user?->name ?? '') }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Nom</label>
                        <input type="text" name="last_name[]" class="form-control" 
                        value="{{ old('last_name.0', $user?->surname ?? '') }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user?->email ?? '') }}" required>
                    </div>
                    <input type="hidden" name="type[]" value="adulte">
                </div>

                {{-- Autres adultes --}}
                @for ($i = 1; $i < $adults; $i++)
                    <div class="border p-3 mb-3">
                        <h5>Adulte {{ $i + 1 }}</h5>
                        <div class="mb-2">
                            <label>Prénom</label>
                            <input type="text" name="first_name[]" class="form-control" value="{{ old("first_name.$i") }}" required>
                        </div>
                        <div class="mb-2">
                            <label>Nom</label>
                            <input type="text" name="last_name[]" class="form-control" value="{{ old("last_name.$i") }}" required>
                        </div>
                        <input type="hidden" name="type[]" value="adulte">
                    </div>
                @endfor

                {{-- Enfants --}}
                @for ($i = 0; $i < $children; $i++)
                    <div class="border p-3 mb-3">
                        <h5>Enfant {{ $i + 1 }}</h5>
                        <div class="mb-2">
                            <label>Prénom</label>
                            <input type="text" name="first_name[]" class="form-control" value="{{ old('first_name.' . ($adults + $i)) }}" required>
                        </div>
                        <div class="mb-2">
                            <label>Nom</label>
                            <input type="text" name="last_name[]" class="form-control" value="{{ old('last_name.' . ($adults + $i)) }}" required>
                        </div>
                        <input type="hidden" name="type[]" value="children">
                    </div>
                @endfor

                {{-- Bébés --}}
                @for ($i = 0; $i < $babies; $i++)
                    <div class="border p-3 mb-3">
                        <h5>Bébé {{ $i + 1 }}</h5>
                        <div class="mb-2">
                            <label>Prénom</label>
                            <input type="text" name="first_name[]" class="form-control" value="{{ old('first_name.' . ($adults + $children + $i)) }}" required>
                        </div>
                        <div class="mb-2">
                            <label>Nom</label>
                            <input type="text" name="last_name[]" class="form-control" value="{{ old('last_name.' . ($adults + $children + $i)) }}" required>
                        </div>
                        <input type="hidden" name="type[]" value="babies">
                    </div>
                @endfor

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary w-50">
                        🛒 Ajouter au panier
                    </button>

                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-50">
                        🔙 Chercher nouveau vol
                    </a>
                </div>


            </form>
        </div>
    </div>
</div>
@endsection