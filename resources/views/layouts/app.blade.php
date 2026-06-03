<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BalamGo - @yield('title', 'WebGIS Bandar Lampung')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">

    <style>
        :root { --primary: #07549b; --accent: #27AE60; }
        body { font-family: 'Segoe UI', sans-serif; }
        .navbar-brand { font-weight: 800; color: var(--primary) !important; }
        .navbar {
            border-bottom: 1px solid rgba(15, 35, 56, .08);
        }
        .navbar .nav-link {
            color: #667085;
            font-weight: 650;
            padding-inline: 1rem !important;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--primary);
        }
        .navbar .nav-link.active {
            position: relative;
        }
        .navbar .nav-link.active::after {
            content: "";
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: .25rem;
            height: 3px;
            border-radius: 999px;
            background: var(--primary);
        }
        .admin-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            min-height: 40px;
            padding: .55rem 1rem;
            border-radius: 8px;
            background: var(--primary);
            color: #fff !important;
            font-weight: 750;
            text-decoration: none;
            box-shadow: 0 10px 24px rgba(7, 84, 155, .22);
        }
        .admin-pill:hover {
            background: #06457e;
            color: #fff;
        }
        @media (max-width: 991.98px) {
            .navbar .nav-link.active::after {
                display: none;
            }
            .admin-pill {
                margin-top: .5rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('home') }}">BalamGo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('peta') ? 'active' : '' }}" href="{{ route('peta') }}">Interactive Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                </ul>
                <div class="d-flex align-items-lg-center">
                    @auth
                        <a class="admin-pill" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link ms-lg-2">Logout</button>
                        </form>
                    @else
                        <a class="admin-pill" href="{{ route('login') }}">Admin Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
