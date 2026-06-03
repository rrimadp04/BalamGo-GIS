@extends('layouts.app')

@section('title', $mitigasi->nama_lokasi)

@push('styles')
<style>
    body { background: #f5f7f9; color: #1b2430; }
    .facility-hero { min-height: 500px; display: flex; align-items: flex-end; position: relative; color: #fff; background: linear-gradient(180deg, rgba(8,20,36,.12), rgba(8,20,36,.76)), url('https://images.unsplash.com/photo-1584384689201-e0bcbe2c7f8f?auto=format&fit=crop&w=1800&q=80') center/cover; }
    .facility-hero::after { content: ""; position: absolute; inset: auto 0 0; height: 120px; background: linear-gradient(180deg, rgba(245,247,249,0), #f5f7f9); }
    .hero-inner { position: relative; z-index: 2; padding-bottom: 58px; }
    .badge-line { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
    .hero-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; background: #07549b; color: #fff; font-weight: 850; text-transform: uppercase; font-size: .8rem; }
    .hero-badge.green { background: #55efc4; color: #075343; }
    .hero-title { max-width: 820px; font-size: clamp(2rem, 4vw, 3.4rem); font-weight: 850; line-height: 1.05; }
    .hero-meta { color: rgba(255,255,255,.9); font-size: 1.05rem; }
    .content-section { padding: 42px 0 76px; }
    .info-card, .main-card, .near-card { border: 1px solid #e0e7ef; border-radius: 10px; background: #fff; box-shadow: 0 14px 34px rgba(18,32,52,.08); }
    .info-card { padding: 24px; }
    .info-card h2, .main-card h2 { color: #07549b; font-size: 1.35rem; font-weight: 850; margin-bottom: 18px; }
    .contact-row { display: flex; gap: 14px; align-items: center; margin: 20px 0; }
    .contact-icon { width: 44px; height: 44px; display: grid; place-items: center; border-radius: 99px; background: #ffe1e1; color: #dc2626; font-size: 1.15rem; }
    .contact-icon.blue { background: #dbeafe; color: #07549b; }
    .main-card { padding: clamp(22px, 3vw, 34px); }
    .main-card h2 { color: #1b2430; }
    .main-card p { color: #5f6b7a; line-height: 1.75; }
    .service-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-top: 22px; }
    .service-box { min-height: 88px; display: grid; place-items: center; text-align: center; border-radius: 10px; background: #f0f2f4; color: #313b49; font-weight: 750; }
    .service-box i { display: block; color: #07549b; font-size: 1.45rem; margin-bottom: 6px; }
    #facilityMap { height: 330px; border-radius: 10px; overflow: hidden; background: #d9ebe5; }
    .availability-row { display: flex; justify-content: space-between; gap: 12px; padding: 11px 0; border-bottom: 1px solid #eef2f6; color: #5f6b7a; }
    .availability-row:last-child { border-bottom: 0; }
    .availability-row strong { color: #1b2430; }
    .near-section { padding: 58px 0; background: #eef1f4; }
    .near-card { height: 100%; overflow: hidden; }
    .near-card img { width: 100%; height: 190px; object-fit: cover; }
    .near-card .body { padding: 18px; }
    .price { color: #07549b; font-weight: 850; }
    @media (max-width: 991.98px) { .service-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
@endpush

@section('content')
@php
    $services = collect(explode(',', $mitigasi->layanan ?: 'Emergency Response,Medical Support,Information Center,Public Safety'))->map(fn($item) => trim($item))->filter()->take(4);
    $images = [
        'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80',
    ];
@endphp

<section class="facility-hero">
    <div class="container hero-inner">
        <div class="badge-line">
            <span class="hero-badge">Mitigation Hub</span>
            <span class="hero-badge green"><i class="bi bi-check-circle"></i> Verified Facility</span>
        </div>
        <h1 class="hero-title">{{ $mitigasi->nama_lokasi }}</h1>
        <div class="hero-meta"><i class="bi bi-geo-alt"></i> {{ $mitigasi->alamat ?: ($mitigasi->kecamatan ?: 'Bandar Lampung') }}</div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="info-card mb-4">
                    <h2>Emergency Contacts</h2>
                    <div class="contact-row">
                        <span class="contact-icon"><i class="bi bi-telephone"></i></span>
                        <div><small class="fw-bold text-muted">General Emergency</small><div class="fw-bold fs-5">{{ $mitigasi->kontak ?: '(0721) 703312' }}</div></div>
                    </div>
                    <div class="contact-row">
                        <span class="contact-icon blue"><i class="bi bi-envelope"></i></span>
                        <div><small class="fw-bold text-muted">Administration</small><div class="fw-bold">kontak@balamgo.id</div></div>
                    </div>
                    <a class="btn btn-primary w-100 rounded-2 fw-bold mt-2" target="_blank" href="https://www.google.com/maps?q={{ $mitigasi->latitude }},{{ $mitigasi->longitude }}"><i class="bi bi-signpost"></i> Get Directions</a>
                </div>

                <div class="info-card">
                    <h2>Availability</h2>
                    <div class="availability-row"><span>Status</span><strong class="text-success">{{ $mitigasi->status_aktif ?: 'Open 24/7' }}</strong></div>
                    <div class="availability-row"><span>Capacity</span><strong>{{ $mitigasi->kapasitas ?: 'Siap digunakan' }}</strong></div>
                    <div class="availability-row"><span>Response</span><strong>&lt; 15 Mins</strong></div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="main-card mb-4">
                    <h2>About the Facility</h2>
                    <p>{{ $mitigasi->nama_lokasi }} adalah bagian dari jaringan fasilitas mitigasi BalamGo untuk membantu masyarakat menemukan layanan penting saat keadaan darurat. Informasi lokasi, kontak, kapasitas, dan layanan disajikan agar respons bisa lebih cepat dan terarah.</p>
                    <div class="service-grid">
                        @foreach($services as $service)
                            <div class="service-box"><div><i class="bi bi-activity"></i>{{ $service }}</div></div>
                        @endforeach
                    </div>
                </div>

                <div class="main-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0">Exact Location</h2>
                        <a href="{{ route('peta') }}" class="fw-bold text-decoration-none">Back to Map</a>
                    </div>
                    <div id="facilityMap"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="near-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="text-success fw-bold text-uppercase">Discover More</div>
                <h2 class="fw-bold mb-0">Wisata Terdekat</h2>
            </div>
            <a href="{{ route('peta') }}" class="fw-bold text-decoration-none">View All Attractions <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @foreach($wisataTerdekat as $index => $wisata)
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('wisata.show', $wisata) }}" class="text-decoration-none">
                        <article class="near-card">
                            <img src="{{ $wisata->foto ? Storage::url($wisata->foto) : $images[$index % count($images)] }}" alt="{{ $wisata->nama_wisata }}">
                            <div class="body">
                                <h3 class="h5 fw-bold text-dark">{{ $wisata->nama_wisata }}</h3>
                                <p class="text-muted mb-3">{{ Str::limit($wisata->deskripsi ?: 'Destinasi populer di Bandar Lampung.', 76) }}</p>
                                <div class="d-flex justify-content-between"><span class="text-muted"><i class="bi bi-star-fill text-warning"></i> 4.{{ 7 + $index }}</span><span class="price">{{ $wisata->harga_tiket ? 'Rp '.$wisata->harga_tiket : 'Open' }}</span></div>
                            </div>
                        </article>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
const facilityMap = L.map('facilityMap', { zoomControl: true }).setView([{{ $mitigasi->latitude }}, {{ $mitigasi->longitude }}], 14);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(facilityMap);
L.marker([{{ $mitigasi->latitude }}, {{ $mitigasi->longitude }}]).addTo(facilityMap);
</script>
@endpush
