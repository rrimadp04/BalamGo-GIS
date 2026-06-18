@extends('layouts.app')

@section('title', 'Tentang BalamGo')

@push('styles')
<style>
    :root {
        --about-primary: #07549b;
        --about-deep: #12304f;
        --about-muted: #657084;
        --about-soft: #f5f7f9;
        --about-border: #dde6ef;
        --about-mint: #55efc4;
    }

    body {
        background: #fff;
        color: #1d2735;
    }

    .about-hero {
        min-height: 590px;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        text-align: center;
        background:
            linear-gradient(180deg, rgba(255,255,255,.32) 0%, rgba(255,255,255,.64) 54%, rgba(255,255,255,.94) 100%),
            url('{{ asset('Baground_About.jpg') }}') center/cover;
    }

    .about-hero::after {
        content: "";
        position: absolute;
        inset: auto 0 0;
        height: 150px;
        background: linear-gradient(180deg, rgba(245,247,249,0), var(--about-soft));
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 920px;
        margin: 0 auto;
        padding: 5rem 1rem 8rem;
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem .9rem;
        border-radius: 999px;
        background: rgba(7, 84, 155, .1);
        color: var(--about-primary);
        font-weight: 850;
        margin-bottom: 1rem;
    }

    .hero-title {
        color: var(--about-primary);
        font-size: clamp(2.3rem, 5vw, 4.6rem);
        font-weight: 900;
        line-height: 1.02;
        letter-spacing: 0;
        margin-bottom: 1rem;
    }

    .hero-copy {
        max-width: 740px;
        margin: 0 auto;
        color: #4d5969;
        font-size: 1.08rem;
        line-height: 1.7;
        font-weight: 600;
    }

    .section {
        padding: 5rem 0;
    }

    .section-soft {
        background: var(--about-soft);
    }

    .eyebrow {
        color: #0f8a68;
        font-size: .8rem;
        font-weight: 900;
        letter-spacing: .1em;
        text-transform: uppercase;
        margin-bottom: .6rem;
    }

    .section-heading {
        color: #1d2735;
        font-size: clamp(1.8rem, 3vw, 2.65rem);
        font-weight: 900;
        line-height: 1.15;
        margin-bottom: 1rem;
    }

    .section-copy {
        color: var(--about-muted);
        line-height: 1.8;
        font-size: 1.02rem;
    }

    .trust-card {
        min-height: 270px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        background: #eef0f2;
        padding: 2rem;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.7);
    }

    .trust-card::after {
        content: "\F47F";
        position: absolute;
        top: 24px;
        right: 28px;
        color: rgba(28, 44, 63, .09);
        font-family: "bootstrap-icons";
        font-size: 5.4rem;
        line-height: 1;
    }

    .icon-tile {
        width: 56px;
        height: 56px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--about-primary);
        color: #fff;
        font-size: 1.45rem;
        box-shadow: 0 12px 28px rgba(7,84,155,.2);
    }

    .trust-card h3 {
        margin: 1.2rem 0 .5rem;
        color: #1d2735;
        font-weight: 900;
    }

    .objective-card {
        height: 100%;
        padding: 2rem;
        border: 1px solid var(--about-border);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 18px 42px rgba(18, 32, 52, .07);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .objective-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 56px rgba(18, 32, 52, .12);
    }

    .objective-card i {
        display: inline-block;
        color: var(--about-primary);
        font-size: 2.2rem;
        margin-bottom: 1.3rem;
    }

    .objective-card h3 {
        color: #263140;
        font-size: 1.15rem;
        font-weight: 900;
        margin-bottom: .75rem;
    }

    .objective-card p {
        color: var(--about-muted);
        line-height: 1.65;
        margin: 0;
    }

    .stack-card {
        min-height: 150px;
        display: grid;
        place-items: center;
        text-align: center;
        padding: 1.35rem;
        border: 1px solid var(--about-border);
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(18, 32, 52, .05);
    }

    .stack-icon {
        width: 54px;
        height: 54px;
        display: grid;
        place-items: center;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: #eef2f6;
        color: var(--about-primary);
        font-size: 1.45rem;
    }

    .stack-card strong {
        display: block;
        color: #263140;
        margin-bottom: .25rem;
    }

    .stack-card span {
        color: var(--about-muted);
        font-size: .88rem;
        font-weight: 650;
    }

    .developer-card {
        height: 100%;
        text-align: center;
        border: 1px solid var(--about-border);
        border-radius: 14px;
        background: #fff;
        padding: 2rem 1.3rem;
        box-shadow: 0 18px 40px rgba(18, 32, 52, .07);
    }

    .developer-card.featured {
        transform: translateY(-18px);
        border-color: rgba(7,84,155,.28);
        box-shadow: 0 28px 62px rgba(7, 84, 155, .16);
    }

    .avatar {
        width: 96px;
        height: 96px;
        display: grid;
        place-items: center;
        margin: 0 auto 1.2rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #07549b, #55efc4);
        color: #fff;
        font-size: 2rem;
        font-weight: 900;
        box-shadow: 0 16px 34px rgba(7,84,155,.22);
    }

    .developer-card h3 {
        color: #1d2735;
        font-size: 1.18rem;
        font-weight: 900;
        margin-bottom: .35rem;
    }

    .developer-card p {
        color: var(--about-muted);
        margin: 0;
        font-weight: 650;
    }

    .cta-wrap {
        padding: 5rem 0 6rem;
        background: #fff;
    }

    .cta-card {
        max-width: 1050px;
        margin: 0 auto;
        text-align: center;
        color: #fff;
        border-radius: 14px;
        padding: clamp(2.5rem, 6vw, 4.4rem);
        background:
            linear-gradient(90deg, rgba(7, 63, 117, .98), rgba(7, 84, 155, .95)),
            url('https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1500&q=80') center/cover;
        box-shadow: 0 30px 70px rgba(7,84,155,.18);
    }

    .cta-card h2 {
        font-size: clamp(1.8rem, 3vw, 2.55rem);
        font-weight: 900;
        margin-bottom: 1rem;
    }

    .cta-card p {
        max-width: 650px;
        margin: 0 auto 1.8rem;
        color: rgba(255,255,255,.78);
        line-height: 1.7;
    }

    .cta-actions {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .btn-mint,
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        min-height: 48px;
        padding: .8rem 1.4rem;
        border-radius: 999px;
        font-weight: 900;
        text-decoration: none;
    }

    .btn-mint {
        background: var(--about-mint);
        color: #063f35;
    }

    .btn-ghost {
        border: 1px solid rgba(255,255,255,.35);
        color: #fff;
    }

    .btn-ghost:hover {
        color: #fff;
        background: rgba(255,255,255,.12);
    }

    .about-footer {
        border-top: 1px solid #cfd7df;
        background: #e9ecef;
        color: #667085;
        padding: 2rem 0;
    }

    .footer-brand {
        color: var(--about-primary);
        font-weight: 900;
        margin-bottom: .55rem;
    }

    .footer-link {
        color: #667085;
        text-decoration: none;
        font-weight: 650;
    }

    .footer-link:hover {
        color: var(--about-primary);
    }

    @media (max-width: 991.98px) {
        .developer-card.featured {
            transform: none;
        }
    }

    @media (max-width: 575.98px) {
        .about-hero {
            min-height: 640px;
        }

        .section {
            padding: 4rem 0;
        }

        .cta-actions,
        .btn-mint,
        .btn-ghost {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<main>
    <section class="about-hero">
        <div class="hero-content">
            <div class="hero-kicker">
                <i class="bi bi-geo-alt"></i>
                Tentang BalamGo
            </div>
            <h1 class="hero-title">Pioneering Civic Safety Through Geospatial Intelligence</h1>
            <p class="hero-copy">
                BalamGo menghadirkan infrastruktur informasi geografis untuk Bandar Lampung, menghubungkan eksplorasi wisata dan kesiapsiagaan mitigasi dalam satu pengalaman digital yang jelas.
            </p>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="eyebrow">Our Vision</div>
                    <h2 class="section-heading">About BalamGo</h2>
                    <p class="section-copy">
                        BalamGo dikembangkan sebagai respons terhadap kebutuhan informasi geografis yang cepat, akurat, dan mudah digunakan. Platform ini menyatukan data wisata serta fasilitas mitigasi agar masyarakat dan wisatawan dapat mengambil keputusan dengan lebih percaya diri.
                    </p>
                    <p class="section-copy mb-0">
                        Baik saat mencari destinasi baru maupun membutuhkan informasi fasilitas penting saat darurat, BalamGo membantu menghadirkan peta kota yang lebih informatif, aman, dan terarah.
                    </p>
                </div>
                <div class="col-lg-5">
                    <div class="trust-card">
                        <span class="icon-tile"><i class="bi bi-patch-check"></i></span>
                        <h3>Official & Trusted</h3>
                        <p class="section-copy mb-0">Dirancang untuk mendukung informasi publik yang rapi, mudah diverifikasi, dan siap dipakai dalam navigasi wisata serta mitigasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-soft pt-0">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading text-primary">System Objectives & Benefits</h2>
                <p class="section-copy">Engineered for resilience, designed for clarity.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <article class="objective-card">
                        <i class="bi bi-bounding-box-circles" style="color:#0f8a68"></i>
                        <h3>Precision Mapping</h3>
                        <p>Menampilkan titik wisata dan fasilitas mitigasi dengan kategori yang mudah dibaca dan dipahami.</p>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="objective-card">
                        <i class="bi bi-exclamation-triangle" style="color:#8a3b18"></i>
                        <h3>Risk Mitigation</h3>
                        <p>Membantu mempercepat pencarian titik aman, fasilitas kesehatan, dan layanan penting di sekitar pengguna.</p>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="objective-card">
                        <i class="bi bi-globe-asia-australia"></i>
                        <h3>Economic Growth</h3>
                        <p>Mendukung promosi wisata lokal dengan tampilan destinasi yang lebih terstruktur dan menarik.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row align-items-end g-4 mb-4">
                <div class="col-lg-7">
                    <div class="eyebrow">Our Foundation</div>
                    <h2 class="section-heading">Modern Technology Stack</h2>
                    <p class="section-copy mb-0">Dibangun dengan teknologi web dan pemetaan yang ringan, stabil, dan sesuai untuk sistem informasi geografis.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-6 col-lg">
                    <div class="stack-card"><div><span class="stack-icon"><i class="bi bi-globe"></i></span><strong>QGIS</strong><span>Geospatial Processing</span></div></div>
                </div>
                <div class="col-6 col-lg">
                    <div class="stack-card"><div><span class="stack-icon"><i class="bi bi-code"></i></span><strong>Laravel</strong><span>Robust Backend</span></div></div>
                </div>
                <div class="col-6 col-lg">
                    <div class="stack-card"><div><span class="stack-icon"><i class="bi bi-map"></i></span><strong>Leaflet</strong><span>Map Engine</span></div></div>
                </div>
                <div class="col-6 col-lg">
                    <div class="stack-card"><div><span class="stack-icon"><i class="bi bi-database"></i></span><strong>MySQL</strong><span>Data Integrity</span></div></div>
                </div>
                <div class="col-6 col-lg">
                    <div class="stack-card"><div><span class="stack-icon"><i class="bi bi-grid"></i></span><strong>Bootstrap</strong><span>Interface Support</span></div></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <div class="text-center mb-5">
                <div class="eyebrow">Developer Team</div>
                <h2 class="section-heading">Dibangun oleh Tim BalamGo</h2>
                <p class="section-copy">Tiga pengembang yang merancang pengalaman wisata dan mitigasi agar lebih mudah digunakan.</p>
            </div>
            <div class="row g-4 align-items-stretch justify-content-center">
                <div class="col-md-4 order-md-1">
                    <article class="developer-card">
                        <div class="avatar">NN</div>
                        <h3>Naisyah Nopriani</h3>
                        <p>Developer</p>
                    </article>
                </div>
                <div class="col-md-4 order-md-2">
                    <article class="developer-card featured">
                        <div class="avatar">RP</div>
                        <h3>Rima Dwi Puspitasari</h3>
                        <p>Lead Developer</p>
                    </article>
                </div>
                <div class="col-md-4 order-md-3">
                    <article class="developer-card">
                        <div class="avatar">NT</div>
                        <h3>Naomi Theresia</h3>
                        <p>Developer</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-wrap">
        <div class="container">
            <div class="cta-card">
                <h2>Experience the Future of Our City</h2>
                <p>Explore Bandar Lampung with clearer destination data, mitigation points, and a city map built for everyday decisions.</p>
                <div class="cta-actions">
                    <a href="{{ route('peta') }}" class="btn-mint"><i class="bi bi-map"></i> Open Interactive Map</a>
                    <a href="{{ route('peta') }}" class="btn-ghost"><i class="bi bi-exclamation-octagon"></i> Report An Issue</a>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="about-footer">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-5">
                <div class="footer-brand">BalamGo</div>
                <small>&copy; {{ date('Y') }} BalamGo Bandar Lampung. All rights reserved.</small>
            </div>
            <div class="col-lg-7">
                <div class="d-flex justify-content-lg-end gap-4">
                    <a href="#" class="footer-link">Contact Us</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>
@endsection
