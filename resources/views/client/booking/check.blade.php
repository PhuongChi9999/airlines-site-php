@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>🔍 Retrouver une réservation</h3>

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <p class="text-muted">Vous pouvez retrouver votre réservation en saisissant :</p>
    <ul>
        <li>le <strong>numéro de réservation</strong> (ID), ou</li>
        <li>l’<strong>adresse e-mail</strong> utilisée lors de la réservation</li>
    </ul>

    <form method="POST" action="{{ route('booking.check') }}">
        @csrf

        <div class="mb-3">
            <label for="booking_id" class="form-label">Numéro de réservation</label>
            <input type="number" name="booking_id" class="form-control" value="{{ old('booking_id') }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <button type="submit" class="btn btn-primary w-100">🔎 Rechercher</button>
    </form>
</div>
@endsection
