@extends('layout')
@section('content')

<h2>Créer un aéroport</h2>

<form method="POST" action="{{ route('airports.store') }}">
    @csrf

    <div class="mb-3">
        <label for="code" class="form-label">Code</label>
        <input type="text" name="code" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">Nom de l'aéroport</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">Ville</label>
        <input type="text" name="city" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="country" class="form-label">Pays</label>
        <input type="text" name="country" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Créer</button>
</form>

@endsection
