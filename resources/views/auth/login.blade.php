<x-guest-layout>
    @if(session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <h5 class="fw-bold mb-1 text-dark">Masuk ke Dashboard</h5>
    <p class="text-muted small mb-4">Masukkan kredensial admin Anda</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label text-muted small" for="remember_me">Ingat saya</label>
            </div>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small text-decoration-none">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-login btn-primary w-100 text-white">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="small text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </form>
</x-guest-layout>
