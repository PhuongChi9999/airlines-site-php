<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'L2Air - Réservation de vols')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            padding-top: 70px;
        }
    </style>
</head>
<body>
    {{-- Navbar Bootstrap --}}
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #1d3b55;">
        <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/chilotus.png') }}" alt="Logo" style="height: 24px; margin-right: 6px;">
            L2Air
        </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- Dùng partial thay cho toàn bộ ul.navbar-nav --}}
                @include('layouts.nav')
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
