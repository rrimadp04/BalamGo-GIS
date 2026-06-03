@extends('layouts.app')

@section('title', $wisata->nama_wisata)

@push('styles')
<style>
    body { background: #f5f7f9; color: #1b2430; }
    .detail-hero { min-height: 560px; display: flex; align-items: flex-end; position: relative; overflow: hidden; color: #fff; background: linear-gradient(180deg, rgba(8,20,36,.1), rgba(8,20,36,.78)), url('{{ $wisata->foto ? Storage::url($wisata->foto) : 'https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?auto=format&fit=crop&w=1800&q=80' }}') center/cover; }
    .detail-hero::after { content: ""; position: absolute; inset: auto 0 0; height: 140px; background: linear-gradient(180deg, rgba(245,247,249,0), #f5f7f9); }
    .hero-inner { position: relative; z-index: 2; padding: 0 0 72px; }
    .crumb { color: rgba(255,255,255,.78); font-weight: 650; }
    .hero-title { max-width: 760px; margin: 12px 0 10px; font-size: clamp(2.2rem, 5vw, 4rem); font-weight: 850; line-height: 1.05; }
    .hero-meta { display: flex; flex-wrap: wrap; gap: 10px 16px; align-items: center; color: rgba(255,255,255,.9); font-weight: 650; }
    .rating-pill { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 8px; background: #0d9488; color: #fff; font-weight: 850; }
    .fact-row { margin-top: -44px; position: relative; z-index: 3; }
    .fact-card, .content-card, .side-card, .recommend-card { border: 1px solid #e0e7ef; border-radius: 10px; background: #fff; box-shadow: 0 14px 34px rgba(18, 32, 52, .08); }
    .fact-card { min-height: 100px; padding: 16px 18px; }
    .fact-card i { color: #07549b; font-size: 1.2rem; }
    .fact-label { color: #687384; margin-top: 8px; }
    .fact-value { color: #1b2430; font-size: 1.15rem; font-weight: 850; }
    .page-section { padding: 34px 0 70px; }
    .content-card { padding: clamp(20px, 3vw, 32px); }
    .content-card h2, .side-card h2 { color: #1b2430; font-size: 1.45rem; font-weight: 850; margin-bottom: 14px; }
    .content-card p { color: #5f6b7a; line-height: 1.75; }
    .amenity-grid { display: grid; grid-template-columns: repeat(5, minmax(100px, 1fr)); gap: 14px; }
    .amenity { min-height: 92px; display: grid; place-items: center; text-align: center; padding: 14px; border-radius: 10px; background: #f0f2f4; color: #313b49; font-weight: 750; }
    .amenity i { font-size: 1.35rem; margin-bottom: 6px; }
    .photo-grid { display: grid; grid-template-columns: 1.2fr .8fr .8fr; gap: 16px; }
    .photo-grid img { width: 100%; height: 190px; object-fit: cover; border-radius: 10px; box-shadow: 0 12px 24px rgba(18,32,52,.08); }
    .photo-grid img:first-child { grid-row: span 2; height: 396px; }
    .side-card { padding: 20px; position: sticky; top: 84px; }
    #locationMap { height: 260px; border-radius: 10px; overflow: hidden; background: #bfe9e6; }
    .mitigation-list { display: grid; gap: 10px; }
    .mitigation-item { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 9px; background: #fff; border: 1px solid #e7edf3; }
    .mitigation-icon { width: 38px; height: 38px; display: grid; place-items: center; border-radius: 99px; background: #ffe2e2; color: #dc2626; }
    .recommend-card { overflow: hidden; height: 100%; }
    .recommend-card img { width: 100%; height: 170px; object-fit: cover; }
    .recommend-card .body { padding: 16px; }
    .price { color: #07549b; font-weight: 850; }
    @media (max-width: 991.98px) { .amenity-grid { grid-template-columns: repeat(2, 1fr); } .photo-grid { grid-template-columns: 1fr; } .photo-grid img, .photo-grid img:first-child { height: 220px; } .side-card { position: static; } }
</style>
@endpush

@section('content')
@php
    $facilities = collect(explode(',', $wisata->fasilitas ?: 'Toilet,Mushola,Parkir,Kafe,Spot Foto'))->map(fn($item) => trim($item))->filter()->take(6);
    $gallery = [
        'https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
    ];
@endphp
<section class="detail-hero">
    <div class="container hero-inner">
        <div class="crumb">Tourism &bull; Bandar Lampung</div>
        <h1 class="hero-title">{{ $wisata->nama_wisata }}</h1>
        <div class="hero-meta">
            <span class="rating-pill"><i class="bi bi-star-fill"></i> 4.8</span>
            <span><i class="bi bi-geo-alt"></i> {{ $wisata->alamat ?: ($wisata->kecamatan ?: 'Bandar Lampung') }}</span>
        </div>
    </div>
</section>

<section class="fact-row">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-lg-3"><div class="fact-card"><i class="bi bi-cash-stack"></i><div class="fact-label">Entrance Fee</div><div class="fact-value">{{ $wisata->harga_tiket ? 'Rp '.$wisata->harga_tiket : 'Cek lokasi' }}</div></div></div>
            <div class="col-6 col-lg-3"><div class="fact-card"><i class="bi bi-clock"></i><div class="fact-label">Open Hours</div><div class="fact-value">{{ $wisata->jam_operasional ?: 'Lihat detail' }}</div></div></div>
            <div class="col-6 col-lg-3"><div class="fact-card"><i class="bi bi-telephone"></i><div class="fact-label">Contact</div><div class="fact-value">{{ $wisata->kontak ?: 'Tersedia' }}</div></div></div>
            <div class="col-6 col-lg-3"><div class="fact-card"><i class="bi bi-map"></i><div class="fact-label">Category</div><div class="fact-value">{{ $wisata->kategori }}</div></div></div>
        </div>
    </div>
</section>

<section class="page-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-card mb-4">
                    <h2>About {{ $wisata->nama_wisata }}</h2>
                    <p>{{ $wisata->deskripsi ?: 'Destinasi ini menjadi salah satu titik menarik di Bandar Lampung untuk dikunjungi bersama keluarga, teman, maupun komunitas. Informasi lokasi, kategori, dan fasilitas dirangkum agar perjalanan terasa lebih mudah dan terarah.' }}</p>
                </div>

                <h2 class="h5 fw-bold mb-3">Facilities & Amenities</h2>
                <div class="amenity-grid mb-4">
                    @foreach($facilities as $facility)
                        <div class="amenity"><div><i class="bi bi-check2-circle"></i><br>{{ $facility }}</div></div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 fw-bold mb-0">Capture the Moment</h2>
                    <a href="{{ route('peta') }}" class="fw-bold text-decoration-none">Back to Map <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="photo-grid mb-5">
                    @foreach($gallery as $image)
                        <img src="{{ $image }}" alt="Galeri {{ $wisata->nama_wisata }}">
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="side-card">
                    <h2>Location</h2>
                    <div id="locationMap"></div>
                    <a class="btn btn-primary w-100 mt-3 rounded-2 fw-bold" target="_blank" href="https://www.google.com/maps?q={{ $wisata->latitude }},{{ $wisata->longitude }}">Get Directions</a>
                    <hr>
                    <h2 class="h5"><i class="bi bi-shield-fill-exclamation text-danger"></i> Fasilitas Mitigasi Terdekat</h2>
                    <div class="mitigation-list">
                        <div class="mitigation-item"><span class="mitigation-icon"><i class="bi bi-hospital"></i></span><div><strong>RSUD Abdul Moeloek</strong><br><small>Rumah Sakit Umum</small></div></div>
                        <div class="mitigation-item"><span class="mitigation-icon"><i class="bi bi-truck"></i></span><div><strong>Damkar Bandar Lampung</strong><br><small>Fire Station</small></div></div>
                        <div class="mitigation-item"><span class="mitigation-icon"><i class="bi bi-broadcast"></i></span><div><strong>BPBD Lampung</strong><br><small>Disaster Agency</small></div></div>
                    </div>
                    <button class="btn btn-outline-danger w-100 mt-3 rounded-2 fw-bold"><i class="bi bi-asterisk"></i> Report Hazard</button>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-end justify-content-between mt-5 mb-3">
            <div><h2 class="h4 fw-bold mb-1">Similar Recommendations</h2><p class="text-muted mb-0">Explore other destinations in Bandar Lampung</p></div>
        </div>
        <div class="row g-4">
            @foreach($rekomendasi as $index => $item)
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('wisata.show', $item) }}" class="text-decoration-none">
                        <article class="recommend-card">
                            <img src="{{ $item->foto ? Storage::url($item->foto) : $gallery[$index % count($gallery)] }}" alt="{{ $item->nama_wisata }}">
                            <div class="body">
                                <div class="text-primary small fw-bold">{{ $item->kategori }}</div>
                                <h3 class="h5 fw-bold text-dark mt-1">{{ $item->nama_wisata }}</h3>
                                <div class="d-flex justify-content-between align-items-center"><span class="text-muted"><i class="bi bi-star-fill text-success"></i> 4.{{ 5 + $index }}</span><span class="price">{{ $item->harga_tiket ? 'Rp '.$item->harga_tiket : 'Open' }}</span></div>
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
const detailMap = L.map('locationMap', { zoomControl: true, dragging: false, scrollWheelZoom: false }).setView([{{ $wisata->latitude }}, {{ $wisata->longitude }}], 14);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(detailMap);
L.marker([{{ $wisata->latitude }}, {{ $wisata->longitude }}]).addTo(detailMap);
</script>
@endpush
