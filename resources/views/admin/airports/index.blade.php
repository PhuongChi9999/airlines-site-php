@extends('layout')

@section('content')
    <div class="container mt-4">
        <h2>🛬 Liste des aéroports</h2>

        {{-- Only admin see button Créer --}}
        @auth
            @if(Auth::user()->is_admin)
                <a href="{{ route('airports.create') }}" class="btn btn-success mb-3">➕ Créer un aéroport</a>
            @endif
        @endauth

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Ville</th>
                    <th>Pays</th>
                    {{-- Cột action chỉ hiện if là admin --}}
                    @auth
                        @if(Auth::user()->is_admin)
                            <th>Actions</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach($airports as $airport)
                    <tr>
                        <td>{{ $airport->name }}</td>
                        <td>{{ $airport->city }}</td>
                        <td>{{ $airport->country }}</td>

                        {{-- if admin mới thấy các hành động --}}
                        @auth
                            @if(Auth::user()->is_admin)
                                <td class="d-flex gap-2">
                                    <a href="{{ route('airports.edit', $airport->id) }}" class="btn btn-primary btn-sm">✏️ Modifier</a>

                                    <form action="{{ route('airports.destroy', $airport->id) }}" method="POST" onsubmit="return confirm('Supprimer cet aéroport ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                    </form>
                                </td>
                            @endif
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
