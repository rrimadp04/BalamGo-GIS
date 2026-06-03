<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BalamGo') }} - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f4f8; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-card { width: 100%; max-width: 420px; border-radius: 16px; border: 1px solid #dce3ea; box-shadow: 0 24px 56px rgba(16,31,52,.12); }
        .login-header { background: #07549b; border-radius: 16px 16px 0 0; padding: 28px 32px 24px; color: #fff; }
        .login-body { padding: 32px; }
        .form-control:focus { border-color: #07549b; box-shadow: 0 0 0 3px rgba(7,84,155,.15); }
        .btn-login { background: #07549b; border: 0; min-height: 46px; font-weight: 700; letter-spacing: .02em; }
        .btn-login:hover { background: #06457e; }
    </style>
</head>
<body>
    <div class="login-card bg-white">
        <div class="login-header">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-geo-alt-fill fs-4"></i>
                <span class="fw-bold fs-5">BalamGo</span>
            </div>
            <div class="opacity-75 small">WebGIS Wisata & Mitigasi Bandar Lampung</div>
        </div>
        <div class="login-body">
            {{ $slot }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
