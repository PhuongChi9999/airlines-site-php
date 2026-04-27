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

    .search-box {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        background-color: #fff;
    }

    .nav-top {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1rem;
    }

    .nav-top .nav-link {
        font-weight: 500;
    }
</style>

<div class="hero text-center">
    <div class="container hero-content">
        <h1 class="display-4 fw-bold">EXPLOREZ SANS LIMITES</h1>
        <p class="lead">avec L2info</p>
        <a href="#" class="btn btn-danger btn-lg">En savoir plus</a>
    </div>
</div>

<div class="container mt-5">
    <div class="search-box shadow" style="margin-top: -100px; position: relative; z-index: 2;">
    <ul class="nav nav-tabs nav-top mb-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Rechercher un vol</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('my-booking') ? 'active' : '' }}" href="{{ route('booking.checkForm') }}">
                Mes réservations
            </a>
        </li>
    </ul>


        <form method="GET" action="{{ route('recherche') }}">
            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="trip_type" id="allerSimple" value="aller_simple" checked>
                    <label class="form-check-label" for="allerSimple">Aller simple</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="trip_type" id="allerRetour" value="aller_retour">
                    <label class="form-check-label" for="allerRetour">Aller-retour</label>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Aéroport de départ</label>
                    <select name="departure_airport_id" class="form-select">
                        @foreach($aeroports as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Aéroport d’arrivée</label>
                    <select name="arrival_airport_id" class="form-select">
                        @foreach($aeroports as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Départ</label>
                    <input type="date" name="departure_date" id="departure_date" class="form-control">
                </div>
                <div class="col-md-6" id="retourCol">
                    <label class="form-label">Retour</label>
                    <input type="date" name="return_date" id="return_date" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Classe</label>
                    <select name="selected_class" class="form-select">
                        <option value="E">Classe Économique</option>
                        <option value="B">Classe Affaires</option>
                        <option value="F">Première Classe</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Passagers</label>
                    <div class="row g-2">
                        <div class="col">
                            <label class="form-label">Adultes (12+)</label>
                            <input type="number" name="adults" class="form-control" min="1" value="1" placeholder="Adultes">
                        </div>
                        <div class="col">
                            <label class="form-label">Enfants (2-11)</label>
                            <input type="number" name="children" class="form-control" min="0" value="0" placeholder="Enfants">
                        </div>
                        <div class="col">
                            <label class="form-label">Bébés (-2)</label>
                            <input type="number" name="babies" class="form-control" min="0" value="0" placeholder="Bébés">
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-danger btn-lg">Rechercher un vol</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const retourCol = document.getElementById('retourCol');
    const departureDate = document.getElementById('departure_date');
    const returnDate = document.getElementById('return_date');

    function toggleRetour() {
        if (document.getElementById('allerSimple').checked) {
            retourCol.style.display = 'none';
        } else {
            retourCol.style.display = 'block';
        }
    }

    document.getElementById('allerSimple').addEventListener('change', toggleRetour);
    document.getElementById('allerRetour').addEventListener('change', toggleRetour);
    toggleRetour();

    departureDate.addEventListener('change', function () {
        returnDate.min = this.value;
    });
});
</script>
@endsection
