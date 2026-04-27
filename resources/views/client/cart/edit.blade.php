
@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>✏️ Modifier la réservation</h3>

    <form method="POST" action="{{ route('cart.update', $booking->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="selected_class" value="{{ $selected_class }}">

        <div class="mb-3">
            <select name="seat_class" id="seat_class" class="form-select" required>
                @foreach($flight->seats as $seat)
                    <option value={{ $seat->code }} {{ $selected_class === $seat->code ? 'selected' : '' }}>
                        {{ $seat->class }} — {{ number_format($seat->price, 0, ',', ' ') }} €
                    </option>
                @endforeach
            </select>
        </div>

        <h5 class="mt-4">👤 Passagers existants</h5>
        <div id="passenger-list">
            @foreach($booking->passengers as $index => $passenger)
                <div class="border rounded p-3 mb-2">
                    <input type="hidden" name="existing_ids[]" value="{{ $passenger->id }}">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="existing_first_name[]" class="form-control" placeholder="Prénom" value="{{ $passenger->first_name }}" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="existing_last_name[]" class="form-control" placeholder="Nom" value="{{ $passenger->last_name }}" required>
                        </div>
                        <div class="col-md-3">
                            <select name="existing_type[]" class="form-select">
                                <option value="adulte" {{ $passenger->type === 'adulte' ? 'selected' : '' }}>Adulte</option>
                                <option value="enfant" {{ $passenger->type === 'enfant' ? 'selected' : '' }}>Enfant</option>
                                <option value="bébé" {{ $passenger->type === 'bébé' ? 'selected' : '' }}>Bébé</option>
                            </select>
                        </div>
												<div class="col-md-1">
														<input type="checkbox" name="remove_existing[]" value="{{ $passenger->id }}"> ❌
												</div>
                    </div>
                </div>
            @endforeach
        </div>

        <h5 class="mt-4">➕ Ajouter de nouveaux passagers</h5>
        <div id="new-passengers"></div>

        <button type="button" onclick="addPassenger()" class="btn btn-outline-secondary mb-3">+ Ajouter un passager</button>

        <div class="d-flex gap-2 justify-content-center mt-4">
    <button type="submit" class="btn btn-primary w-50">
        💾 Enregistrer
    </button>

    <a href="{{ route('client.cart.index') }}" class="btn btn-outline-secondary w-50">
        ❌ Annuler
    </a>
</div>

    </form>
</div>

<script>
    let passengerCount = 0;

    function addPassenger() {
        const container = document.getElementById('new-passengers');
        const html = `
            <div class="border rounded p-3 mb-2">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="new_first_name[]" class="form-control" placeholder="Prénom" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="new_last_name[]" class="form-control" placeholder="Nom" required>
                    </div>
                    <div class="col-md-4">
                        <select name="new_type[]" class="form-select">
                            <option value="adulte">Adulte</option>
                            <option value="enfant">Enfant</option>
                            <option value="bébé">Bébé</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        passengerCount++;
    }
</script>
@endsection
