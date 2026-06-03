@extends('layouts.app')

@section('title', 'Jelajahi Bandar Lampung')

@push('styles')
<style>
    :root {
        --balam-primary: #07549b;
        --balam-primary-2: #0f76bd;
        --balam-ink: #152033;
        --balam-muted: #657084;
        --balam-mint: #55efc4;
        --balam-soft: #f5f8fb;
        --balam-border: #dfe7f0;
    }

    body {
        background: #fff;
        color: var(--balam-ink);
    }

    .navbar {
        position: sticky;
        top: 0;
        z-index: 50;
        border-bottom: 1px solid rgba(15, 35, 56, .08);
    }

    .navbar .nav-link {
        color: #667085;
        font-weight: 650;
        padding-inline: 1rem !important;
    }

    .navbar .nav-link:hover,
    .navbar .nav-link.active {
        color: var(--balam-primary);
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
        background: var(--balam-primary);
    }

    .admin-pill {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        min-height: 40px;
        padding: .55rem 1rem;
        border-radius: 8px;
        background: var(--balam-primary);
        color: #fff !important;
        font-weight: 750;
        text-decoration: none;
        box-shadow: 0 10px 24px rgba(7, 84, 155, .22);
    }

    .landing-hero {
        min-height: calc(100vh - 56px);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(90deg, rgba(7, 52, 96, .92) 0%, rgba(7, 82, 145, .74) 42%, rgba(7, 82, 145, .35) 72%, rgba(7, 82, 145, .12) 100%),
            url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=80') center/cover;
    }

    .landing-hero::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 190px;
        background: linear-gradient(180deg, rgba(245, 248, 251, 0) 0%, var(--balam-soft) 78%);
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 760px;
        color: #fff;
        padding-block: 5rem 10rem;
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .45rem .8rem;
        border: 1px solid rgba(255, 255, 255, .28);
        border-radius: 999px;
        background: rgba(255, 255, 255, .12);
        backdrop-filter: blur(10px);
        font-weight: 700;
        font-size: .85rem;
        margin-bottom: 1.35rem;
    }

    .hero-title {
        font-weight: 760;
        font-size: clamp(2.35rem, 6vw, 5.2rem);
        line-height: 1.02;
        letter-spacing: 0;
        margin-bottom: 1.25rem;
    }

    .hero-copy {
        max-width: 620px;
        color: rgba(255, 255, 255, .82);
        font-size: clamp(1rem, 2vw, 1.2rem);
        line-height: 1.7;
        margin-bottom: 1.8rem;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .85rem;
    }

    .btn-balam,
    .btn-balam-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        min-height: 48px;
        padding: .8rem 1.25rem;
        border-radius: 8px;
        font-weight: 750;
        text-decoration: none;
    }

    .btn-balam {
        background: var(--balam-primary);
        color: #fff;
        box-shadow: 0 14px 28px rgba(0, 38, 81, .22);
    }

    .btn-balam:hover,
    .admin-pill:hover {
        background: #06457e;
        color: #fff;
    }

    .btn-balam-outline {
        border: 1px solid rgba(255, 255, 255, .35);
        background: rgba(255, 255, 255, .12);
        color: #fff;
        backdrop-filter: blur(10px);
    }

    .btn-balam-outline:hover {
        background: rgba(255, 255, 255, .2);
        color: #fff;
    }

    .stats-wrap {
        position: relative;
        z-index: 3;
        margin-top: -72px;
    }

    .stat-card {
        min-height: 136px;
        padding: 1.35rem;
        border: 1px solid var(--balam-border);
        border-radius: 8px;
        background: rgba(255, 255, 255, .92);
        box-shadow: 0 16px 44px rgba(17, 39, 71, .08);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 1.25rem;
    }

    .stat-value {
        margin-top: 1rem;
        color: var(--balam-primary);
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1;
    }

    .stat-label {
        color: var(--balam-muted);
        font-weight: 650;
        margin-top: .35rem;
    }

    .section-band {
        background: var(--balam-soft);
        padding: 5.5rem 0;
    }

    .section-title {
        color: var(--balam-primary);
        font-size: 1rem;
        font-weight: 800;
        margin-bottom: .35rem;
    }

    .section-copy {
        color: var(--balam-muted);
        margin: 0;
    }

    .see-all {
        color: var(--balam-primary);
        font-weight: 800;
        text-decoration: none;
    }

    .destination-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--balam-border);
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 18px 42px rgba(25, 51, 86, .08);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .destination-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 54px rgba(25, 51, 86, .13);
    }

    .destination-image {
        position: relative;
        aspect-ratio: 4 / 2.35;
        background: #d8e8ef;
        overflow: hidden;
    }

    .destination-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .destination-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 52%, rgba(0, 0, 0, .32) 100%);
    }

    .badge-soft {
        display: inline-flex;
        align-items: center;
        min-height: 24px;
        padding: .25rem .55rem;
        border-radius: 6px;
        background: rgba(85, 239, 196, .22);
        color: #098a64;
        font-size: .72rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .rating {
        color: #8a4b16;
        font-weight: 780;
        font-size: .9rem;
    }

    .destination-title {
        min-height: 2.8rem;
        color: var(--balam-ink);
        font-weight: 780;
        margin: .8rem 0 .25rem;
        line-height: 1.35;
    }

    .destination-meta {
        color: var(--balam-muted);
        font-size: .92rem;
        min-height: 1.45rem;
    }

    .price {
        color: var(--balam-primary);
        font-weight: 850;
    }

    .feature-band {
        background: #eef1f4;
        padding: 5.5rem 0;
    }

    .feature-item {
        max-width: 360px;
        margin-inline: auto;
    }

    .feature-item h3 {
        color: #3e4858;
        font-size: 1.05rem;
        font-weight: 780;
        margin: 1.2rem 0 .65rem;
    }

    .feature-item p {
        color: var(--balam-muted);
        line-height: 1.65;
        margin: 0;
    }

    .cta-band {
        padding: 4.5rem 0;
        background: #fff;
    }

    .cta-box {
        border-radius: 8px;
        overflow: hidden;
        background:
            linear-gradient(90deg, rgba(7, 64, 117, .98), rgba(7, 84, 155, .94)),
            url('https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1400&q=80') center/cover;
        color: #fff;
        padding: clamp(2rem, 5vw, 4.2rem);
        text-align: center;
    }

    .cta-box p {
        color: rgba(255, 255, 255, .75);
        max-width: 720px;
        margin: 1rem auto 1.7rem;
    }

    .subscribe-form {
        display: flex;
        gap: .75rem;
        max-width: 620px;
        margin: 0 auto;
    }

    .subscribe-form .form-control {
        min-height: 52px;
        border: 0;
        border-radius: 8px;
        padding-inline: 1.25rem;
    }

    .subscribe-form .btn {
        min-width: 170px;
        border: 0;
        border-radius: 8px;
        background: var(--balam-mint);
        color: #063f35;
        font-weight: 850;
    }

    .site-footer {
        border-top: 1px solid #cfd7df;
        background: #e9ecef;
        color: #667085;
        padding: 2rem 0;
    }

    .footer-brand {
        color: var(--balam-primary);
        font-weight: 850;
        margin-bottom: .65rem;
    }

    .footer-link {
        color: #667085;
        text-decoration: none;
        font-weight: 650;
    }

    .footer-link:hover {
        color: var(--balam-primary);
    }

    @media (max-width: 991.98px) {
        .navbar .nav-link.active::after {
            display: none;
        }

        .admin-pill {
            margin-top: .5rem;
        }

        .hero-content {
            padding-block: 4rem 8rem;
        }
    }

    @media (max-width: 575.98px) {
        .landing-hero {
            min-height: 720px;
        }

        .hero-actions,
        .subscribe-form {
            flex-direction: column;
        }

        .btn-balam,
        .btn-balam-outline,
        .subscribe-form .btn {
            width: 100%;
        }

        .stats-wrap {
            margin-top: -56px;
        }
    }
</style>
@endpush

@section('content')
<main>
    <section class="landing-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-kicker">
                    <i class="bi bi-shield-check"></i>
                    Wisata dan mitigasi dalam satu peta
                </div>
                <h1 class="hero-title">Jelajahi Bandar Lampung dengan lebih aman</h1>
                <p class="hero-copy">
                    Temukan destinasi wisata, fasilitas mitigasi, dan titik penting kota dalam pengalaman navigasi yang ringkas, visual, dan siap dipakai.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('peta') }}" class="btn-balam">
                        <i class="bi bi-map"></i>
                        Buka Peta
                    </a>
                    <a href="#destinasi" class="btn-balam-outline">
                        <i class="bi bi-compass"></i>
                        Lihat Destinasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-wrap">
        <div class="container">
            <div class="row g-3 g-lg-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <span class="icon-box" style="background:#d8fff0;color:#078660"><i class="bi bi-signpost-split"></i></span>
                        <div class="stat-value">{{ number_format($totalWisata ?? 0) }}+</div>
                        <div class="stat-label">Total Wisata</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <span class="icon-box" style="background:#ffe2d7;color:#a84a25"><i class="bi bi-exclamation-triangle"></i></span>
                        <div class="stat-value">{{ number_format($totalMitigasi ?? 0) }}</div>
                        <div class="stat-label">Fasilitas Mitigasi</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <span class="icon-box" style="background:#dce9ff;color:#175dad"><i class="bi bi-box-arrow-in-right"></i></span>
                        <div class="stat-value">{{ number_format($totalEvakuasi ?? 0) }}</div>
                        <div class="stat-label">Titik Evakuasi</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <span class="icon-box" style="background:#d7f8ea;color:#087f5b"><i class="bi bi-grid"></i></span>
                        <div class="stat-value">{{ number_format($totalKategori ?? 0) }}</div>
                        <div class="stat-label">Kategori Wisata</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-band" id="destinasi">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
                <div>
                    <div class="section-title">Destinasi Unggulan</div>
                    <p class="section-copy">Rekomendasi terbaik untuk liburan Anda di Bandar Lampung.</p>
                </div>
                <a href="{{ route('peta') }}" class="see-all">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse(($destinasiUnggulan ?? collect()) as $index => $wisata)
                    @php
                        $fallbackImages = [
                            'https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80',
                        ];
                        $imageUrl = $wisata->foto ? Storage::url($wisata->foto) : $fallbackImages[$index % count($fallbackImages)];
                        $price = $wisata->harga_tiket ? 'Rp ' . $wisata->harga_tiket : 'Cek lokasi';
                        $rating = number_format(4.5 + (($index % 4) * 0.1), 1);
                    @endphp
                    <div class="col-md-6 col-xl-3">
                        <article class="destination-card">
                            <div class="destination-image">
                                <img src="{{ $imageUrl }}" alt="{{ $wisata->nama_wisata }}">
                            </div>
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="badge-soft">{{ $wisata->kategori ?? 'Wisata' }}</span>
                                    <span class="rating"><i class="bi bi-star-fill"></i> {{ $rating }}</span>
                                </div>
                                <h2 class="destination-title h6">{{ $wisata->nama_wisata }}</h2>
                                <div class="destination-meta">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $wisata->kecamatan ?? $wisata->kelurahan ?? 'Bandar Lampung' }}
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-3 mt-3">
                                    <span class="price">{{ $price }}</span>
                                    <a href="{{ route('peta') }}" class="btn btn-sm btn-primary rounded-2 px-3">Detail</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    @foreach([
                        ['Puncak Mas', 'Wisata Alam', 'Sukadanaham', 'Rp 20.000', 'https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?auto=format&fit=crop&w=900&q=80'],
                        ['Bukit Sakura', 'Wisata Keluarga', 'Langkapura', 'Rp 15.000', 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=80'],
                        ['Pantai Duta Wisata', 'Wisata Bahari', 'Teluk Betung', 'Rp 10.000', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80'],
                        ['Taman Kota', 'Wisata Budaya/Taman', 'Enggal', 'Gratis', 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80'],
                    ] as $index => $item)
                        <div class="col-md-6 col-xl-3">
                            <article class="destination-card">
                                <div class="destination-image">
                                    <img src="{{ $item[4] }}" alt="{{ $item[0] }}">
                                </div>
                                <div class="p-3">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span class="badge-soft">{{ $item[1] }}</span>
                                        <span class="rating"><i class="bi bi-star-fill"></i> {{ number_format(4.5 + (($index % 4) * 0.1), 1) }}</span>
                                    </div>
                                    <h2 class="destination-title h6">{{ $item[0] }}</h2>
                                    <div class="destination-meta"><i class="bi bi-geo-alt"></i> {{ $item[2] }}</div>
                                    <div class="d-flex align-items-center justify-content-between gap-3 mt-3">
                                        <span class="price">{{ $item[3] }}</span>
                                        <a href="{{ route('peta') }}" class="btn btn-sm btn-primary rounded-2 px-3">Detail</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="feature-band" id="about">
        <div class="container text-center">
            <div class="section-title">Kenapa BalamGo?</div>
            <p class="section-copy mb-5">Solusi digital terintegrasi untuk kenyamanan wisata dan kesiapsiagaan warga Bandar Lampung.</p>

            <div class="row g-5">
                <div class="col-md-4">
                    <div class="feature-item">
                        <span class="icon-box" style="width:64px;height:64px;background:#075fc0;color:#fff;font-size:1.55rem"><i class="bi bi-info-circle"></i></span>
                        <h3>Informasi Wisata</h3>
                        <p>Akses detail destinasi, kategori, harga tiket, jam operasional, dan fasilitas yang tersedia.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <span class="icon-box" style="width:64px;height:64px;background:#55efc4;color:#087052;font-size:1.55rem"><i class="bi bi-shield-fill-check"></i></span>
                        <h3>Fasilitas Mitigasi</h3>
                        <p>Temukan rumah sakit, ruang terbuka, infrastruktur, dan layanan penting terdekat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <span class="icon-box" style="width:64px;height:64px;background:#ffe2d7;color:#a84a25;font-size:1.55rem"><i class="bi bi-map"></i></span>
                        <h3>Peta Interaktif</h3>
                        <p>Visualisasi geografis membantu rute eksplorasi kota terasa lebih efisien dan terarah.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-band">
        <div class="container">
            <div class="cta-box">
                <h2 class="h5 mb-0">Siap menjelajahi Bandar Lampung?</h2>
                <p>Gunakan aplikasi web BalamGo untuk pengalaman navigasi yang lebih baik dan dapatkan pembaruan terkini mengenai wisata serta keamanan kota.</p>
                <form class="subscribe-form" action="#" method="GET">
                    <input type="email" class="form-control" placeholder="Masukkan email Anda" aria-label="Masukkan email Anda">
                    <button type="submit" class="btn">Berlangganan</button>
                </form>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-4">
                <div class="footer-brand">BalamGo</div>
                <p class="mb-0">Portal informasi wisata dan mitigasi bencana terpadu untuk masyarakat dan wisatawan Bandar Lampung.</p>
            </div>
            <div class="col-lg-4">
                <div class="d-flex justify-content-lg-center gap-4">
                    <a href="#" class="footer-link">Contact Us</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <small>&copy; {{ date('Y') }} BalamGo Bandar Lampung. All rights reserved.</small>
            </div>
        </div>
    </div>
</footer>
@endsection
