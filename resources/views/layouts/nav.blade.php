{{-- Navigation partagée pour tous les layouts --}}
<ul class="navbar-nav ms-auto">
    @if(Auth::check() && Auth::user()->is_admin)
        {{-- Administrateur connecté (depuis users table) --}}
        <li class="nav-item"><a href="{{ url('/admin/flights') }}" class="nav-link">Admin - Vols</a></li>
        <li class="nav-item"><a href="{{ url('/admin/airports') }}" class="nav-link">Admin - Aéroports</a></li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light ms-3">Se déconnecter</button>
            </form>
        </li>
    @elseif(Auth::check())
        {{-- Utilisateur connecté normal --}}
        <li class="nav-item">
        <a class="nav-link" href="{{ route('user.profile') }}">
            👤 {{ Auth::user()->name ?? Auth::user()->first_name }}
        </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.flights.index') }}">Vols disponibles</a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light ms-3">Se déconnecter</button>
            </form>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('client.cart.index') }}">Panier</a></li>
    @else
        {{-- Invité --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client.flights.index') }}">Vols disponibles</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Se connecter</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Créer un compte</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('client.cart.index') }}">Panier</a></li>
    @endif
</ul>
