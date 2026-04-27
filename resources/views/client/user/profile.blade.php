@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>👤 Modifier mon profil</h3>

    <form method="POST" action="{{ route('user.profile.update') }}">
        @csrf

        <div class="mb-3">
            <label for="last_name" class="form-label">Nom</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->surname) }}" required>
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">Prénom</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="d-flex justify-content-between">
        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary flex-fill">
                🏠 Accueil
            </a>
            <a href="{{ route('booking.checkForm') }}" class="btn btn-outline-primary flex-fill">
                📦 Mes réservations
            </a>
        </div>
        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary">💾 Mettre à jour</button>
        </div>
        </div>
    </form>
</div>
@endsection
