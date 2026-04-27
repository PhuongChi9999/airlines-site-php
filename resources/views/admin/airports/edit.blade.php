@extends('layout')
@section('content')

<h2>Modifier un aéroport</h2>

<form method="POST" action="{{ route('airports.update', $airport) }}">
    @csrf @method('PUT')
    <div class="mb-3">
        <label for="code" class="form-label">Code</label>
        <input type="text" name="code" value="{{ $airport->code }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Nom de l'aéroport</label>
        <input type="text" name="name" value="{{ $airport->name }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="city" class="form-label">Ville</label>
        <input type="text" name="city" value="{{ $airport->city }}"  class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="country" class="form-label">Pays</label>
        <input type="text" name="country" value="{{ $airport->country }}"  class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>

@endsection
