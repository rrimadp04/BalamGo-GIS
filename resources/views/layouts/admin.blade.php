<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin BalamGo - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { width: 240px; min-height: 100vh; background: #1B6CA8; }
        .sidebar a { color: rgba(255,255,255,.8); text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background: rgba(255,255,255,.15); }
        .main-content { flex: 1; background: #F8F9FA; min-height: 100vh; }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3">
        <div class="text-white fw-bold fs-5 mb-4 pb-2 border-bottom border-secondary">
            <i class="bi bi-geo-alt-fill"></i> BalamGo Admin
        </div>
        <nav class="d-flex flex-column gap-1">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.wisata.index') }}" class="p-2 rounded">
                <i class="bi bi-signpost-2 me-2"></i>Data Wisata
            </a>
            <a href="{{ route('admin.mitigasi.index') }}" class="p-2 rounded">
                <i class="bi bi-shield-check me-2"></i>Data Mitigasi
            </a>
            <a href="{{ route('peta') }}" class="p-2 rounded mt-3">
                <i class="bi bi-map me-2"></i>Lihat Peta
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-left me-1"></i>Logout
                </button>
            </form>
        </nav>
    </div>
    <div class="main-content p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
