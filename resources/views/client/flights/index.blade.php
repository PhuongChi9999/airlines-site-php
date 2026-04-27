@extends('layout')

@section('title', 'Réserver un vol')

@section('content')
<style>
    .hero {
        background-image: url('{{ asset('images/chichi.png') }}');
        background-size: cover;
        background-position: center;
        padding: 8rem 0;
        position: relative;
        color: white;
        text-shadow: 1px 1px 4px black;
    }

    .hero::after {
        content: '';
        background: rgba(0, 0, 0, 0.4);
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .search-wrapper {
        display: flex;
        justify-content: center;
        margin-top: -60px;
    }

    .search-box {
        background-color: rgba(255, 255, 255, 0.5);
        padding: 2rem;
        border-radius: 20px;
        backdrop-filter: blur(6px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 900px;
    }

    .search-box .form-select,
    .search-box .form-control {
        border-radius: 10px;
        font-weight: 500;
    }

    .search-box button {
        font-weight: bold;
    }

    .seat-box {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }

    .seat-price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #d63384;
    }

		.selected {
				border-color: #1d3b55;
		}
</style>

{{-- HERO --}}
<div class="hero text-center">
    <div class="container hero-content">
        <h1 class="display-4 fw-bold">EXPLOREZ SANS LIMITES</h1>
        <p class="lead">avec L2info</p>
        <a href="#" class="btn btn-danger btn-lg">En savoir plus</a>
    </div>
</div>

{{-- SEARCH FORM --}}
<div class="search-wrapper">
    <div class="search-box">
        <form method="GET" action="{{ route('client.flights.index') }}" class="row g-3">
            <div class="col-md-4">
                <select name="departure_airport_id" class="form-select">
                    <option value="">Tous les aéroports de départ</option>
                    @foreach($airports as $a)
                        <option value="{{ $a->id }}" {{ request('departure_airport_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="arrival_airport_id" class="form-select">
                    <option value="">Tous les aéroports d’arrivée</option>
                    @foreach($airports as $a)
                        <option value="{{ $a->id }}" {{ request('arrival_airport_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex">
                <input type="date" name="departure_date" class="form-control me-2" value="{{ request('departure_date') }}">
                <button type="submit" class="btn text-white" style="background-color: #1d3b55;">Rechercher</button>
            </div>
        </form>
    </div>
</div>

@if (session('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif

{{-- FLIGHT RESULTS --}}
<div class="container mt-5">
    <h3 class="mb-3">✈️ Résultats disponibles</h3>
    <div class="row">
        @foreach($flights as $flight)
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $flight->flight_number }} : {{ $flight->departureAirport->name }} → {{ $flight->arrivalAirport->name }}
                        </h5>
                        <p class="card-text">
                            Départ : {{ \Carbon\Carbon::parse($flight->departure_date)->format('d/m/Y H:i') }}
                        </p>

                        {{-- Prix par classe --}}
                        <div class="row text-center">
                        @php
                            $classes = [
                                'E' => 'Classe Économique',
                                'B'   => 'Classe Affaires',
                                'F'   => 'Première Classe'
                            ];
                        @endphp

                        @foreach($classes as $code => $label)
                            @php
                                $seat = $flight->seats->firstWhere('code', $code);
                            @endphp

                            <div class="col-md-4">
                                <div class="seat-box">
                                    <div><strong>{{ $label }}</strong></div>

                                    @if($seat)
                                        <div class="seat-price">{{ number_format($seat->price, 0, ',', ' ') }} €</div>

                                        @php
                                            $remainingSeats = $seat->available_seats;
                                        @endphp

                                        <small>{{ $remainingSeats }} places</small>

                                        {{-- Formulaire dynamique --}}
                                        <form method="GET" action="{{ route('flights.fillForm') }}" class="mt-2">
                                            <input type="hidden" name="departure_flight_id" value="{{ $flight->id }}">
                                            <input type="hidden" name="departure_class" value="{{ $seat->code }}">
                                            <input type="hidden" name="trip_type" value="aller_simple">
                                            <input type="hidden" name="departure_date" value="{{ $flight->departure_date }}">
                                            <input type="hidden" name="return_date" value="">

                                            <div class="row g-1">
                                                <div class="col-4">
                                                    <input type="number" name="adults" class="form-control form-control-sm" value="1" min="1" max="9" placeholder="Adultes" required>
                                                </div>
                                                <div class="col-4">
                                                    <input type="number" name="children" class="form-control form-control-sm" value="0" min="0" max="9" placeholder="Enfants">
                                                </div>
                                                <div class="col-4">
                                                    <input type="number" name="babies" class="form-control form-control-sm" value="0" min="0" max="9" placeholder="Bébés">
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-sm mt-2 text-white w-100" style="background-color: #1d3b55;">
                                                Réserver
                                            </button>
                                        </form>
                                    @else
                                        <div class="text-muted">Non disponible</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        </div>


                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
