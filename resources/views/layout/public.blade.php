<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @livewireStyles
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-4">
        <a href="{{ route('katalog') }}" class="navbar-brand text-primary fw-bold">
            <i class="fa fa-gamepad me-2"></i>{{ config('psone.nama_usaha') }}
        </a>
        <div class="navbar-nav ms-auto align-items-center">
            <a href="{{ route('katalog') }}" class="nav-item nav-link {{ request()->routeIs('katalog') ? 'active' : '' }}">Katalog</a>
            <a href="{{ route('jadwal') }}" class="nav-item nav-link {{ request()->routeIs('jadwal') ? 'active' : '' }}">Jadwal Unit</a>
            <a href="{{ route('login') }}" class="btn btn-sm btn-primary ms-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary ms-2">Daftar</a>
        </div>
    </nav>

    @yield('content')

    <div class="text-center text-muted py-4">&copy; {{ date('Y') }} {{ config('psone.nama_usaha') }}</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
