@extends('layouts.app')

@section('title', 'Galeri BalamGo')

@push('styles')
<style>
    :root {
        --gallery-primary: #07549b;
        --gallery-ink: #172033;
        --gallery-muted: #667085;
        --gallery-soft: #f4f8fb;
        --gallery-border: #dfe7f0;
        --gallery-mint: #55efc4;
        --gallery-coral: #f97316;
    }

    body {
        background: #fff;
        color: var(--gallery-ink);
    }

    @keyframes floatCard {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    @keyframes softPulse {
        0%, 100% { opacity: .7; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.08); }
    }

    @keyframes videoSwap {
        0% { opacity: .55; transform: scale(.985); }
        100% { opacity: 1; transform: scale(1); }
    }

    .gallery-reveal {
        opacity: 0;
        transform: translateY(18px);
    }

    .gallery-reveal.in {
        opacity: 1;
        transform: translateY(0);
        transition: opacity .7s cubic-bezier(.2,.8,.2,1), transform .7s cubic-bezier(.2,.8,.2,1);
    }

    .gallery-hero {
        min-height: 620px;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(90deg, rgba(7, 48, 89, .88) 0%, rgba(7, 84, 155, .64) 48%, rgba(7, 84, 155, .16) 100%),
            url('{{ asset('Baground_Gallery.jpg') }}') center/cover;
    }

    .gallery-hero::after {
        content: "";
        position: absolute;
        inset: auto 0 0;
        height: 150px;
        background: linear-gradient(180deg, rgba(244,248,251,0), var(--gallery-soft));
    }

    .gallery-hero-content {
        position: relative;
        z-index: 2;
        max-width: 760px;
        color: #fff;
        padding-block: 5rem 8rem;
    }

    .gallery-kicker,
    .section-kicker {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-size: .78rem;
        font-weight: 900;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .gallery-kicker {
        padding: .5rem .85rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,.3);
        background: rgba(255,255,255,.14);
        backdrop-filter: blur(10px);
        margin-bottom: 1.1rem;
    }

    .gallery-title {
        font-size: clamp(2.45rem, 6vw, 5rem);
        font-weight: 900;
        line-height: 1.02;
        letter-spacing: 0;
        margin-bottom: 1.2rem;
    }

    .gallery-copy {
        max-width: 700px;
        color: rgba(255,255,255,.84);
        font-size: 1.08rem;
        line-height: 1.75;
        margin: 0;
    }

    .gallery-section {
        padding: 5.25rem 0;
    }

    .gallery-section.soft {
        background: var(--gallery-soft);
    }

    .section-kicker {
        color: #0f8a68;
        margin-bottom: .7rem;
    }

    .section-heading {
        color: var(--gallery-ink);
        font-size: clamp(1.75rem, 3.4vw, 2.8rem);
        font-weight: 900;
        line-height: 1.12;
        letter-spacing: 0;
        margin: 0;
    }

    .section-copy {
        color: var(--gallery-muted);
        line-height: 1.75;
        margin: .9rem 0 0;
    }

    .horizontal-track {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: minmax(330px, 430px);
        gap: 1.05rem;
        align-items: stretch;
        overflow-x: auto;
        overscroll-behavior-inline: contain;
        scroll-snap-type: inline mandatory;
        padding: .25rem .15rem 1.1rem;
        scrollbar-gutter: stable both-edges;
    }

    .horizontal-track > * {
        scroll-snap-align: start;
    }

    .horizontal-track::-webkit-scrollbar {
        height: 9px;
    }

    .horizontal-track::-webkit-scrollbar-thumb {
        background: #c7d5e2;
        border-radius: 999px;
    }

    .gallery-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--gallery-border);
        border-radius: 8px;
        background:
            linear-gradient(#fff, #fff) padding-box,
            linear-gradient(135deg, rgba(7,84,155,.32), rgba(85,239,196,.38), rgba(249,115,22,.24)) border-box;
        box-shadow: 0 18px 42px rgba(18, 36, 62, .08);
        position: relative;
        isolation: isolate;
        transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
    }

    .gallery-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        z-index: -1;
        opacity: 0;
        background: radial-gradient(circle at 18% 12%, rgba(85,239,196,.22), transparent 42%),
                    radial-gradient(circle at 88% 20%, rgba(249,115,22,.14), transparent 38%);
        transition: opacity .24s ease;
    }

    .gallery-card::after {
        content: "";
        position: absolute;
        top: -70%;
        left: -42%;
        width: 54%;
        height: 230%;
        z-index: 3;
        background: linear-gradient(115deg, transparent, rgba(255,255,255,.34), transparent);
        transform: translateX(-120%) rotate(12deg);
        transition: transform .55s ease;
        pointer-events: none;
    }

    .gallery-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 28px 62px rgba(18, 36, 62, .16);
    }

    .gallery-card:hover::before {
        opacity: 1;
    }

    .gallery-card:hover::after {
        transform: translateX(310%) rotate(12deg);
    }

    .gallery-slide-link {
        display: block;
        min-width: 0;
    }

    .gallery-slide-link .video-card {
        transition: transform .28s ease, box-shadow .28s ease, filter .28s ease;
        transform-origin: center center;
        filter: saturate(.98);
    }

    .gallery-slide-link .video-card:hover,
    .gallery-slide-link .video-card.is-active {
        transform: scale(1.02) translateY(-2px);
        box-shadow: 0 26px 54px rgba(8, 15, 24, .28);
        filter: saturate(1.02);
        z-index: 2;
    }

    .gallery-slide-link .video-card:not(.is-active) {
        opacity: .88;
        transform: scale(.98);
    }

    .instagram-card {
        border: 1px solid var(--gallery-border);
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 18px 42px rgba(18, 36, 62, .08);
        position: relative;
        transition: transform .24s ease, box-shadow .24s ease;
    }

    .instagram-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 56px rgba(18, 36, 62, .13);
    }

    .instagram-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform .32s ease, filter .32s ease;
    }

    .instagram-card:hover img {
        transform: scale(1.06);
        filter: saturate(1.08);
    }

    .instagram-card-body {
        padding: 1rem;
    }

    .instagram-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .6rem;
        border-radius: 999px;
        background: rgba(7, 84, 155, .08);
        color: var(--gallery-primary);
        font-size: .72rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: .75rem;
    }

    .instagram-link {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        margin-top: .75rem;
        color: var(--gallery-primary);
        text-decoration: none;
        font-weight: 800;
    }

    .instagram-link:hover {
        color: #06457e;
    }

    .card-image {
        position: relative;
        aspect-ratio: 4 / 2.75;
        overflow: hidden;
        background: #d9e6ef;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .34s ease, filter .34s ease;
    }

    .gallery-card:hover .card-image img {
        transform: scale(1.07);
        filter: saturate(1.08) contrast(1.03);
    }

    .card-image::after {
        content: "";
        position: absolute;
        inset: 45% 0 0;
        background: linear-gradient(180deg, rgba(0,0,0,0), rgba(0,0,0,.38));
        transition: inset .24s ease, background .24s ease;
    }

    .gallery-card:hover .card-image::after {
        inset: 20% 0 0;
        background: linear-gradient(180deg, rgba(0,0,0,0), rgba(7,22,76,.58));
    }

    .card-body-space {
        padding: 1rem;
    }

    .category-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        min-height: 25px;
        padding: .28rem .6rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 900;
    }

    .category-pill {
        background: rgba(85, 239, 196, .22);
        color: #087052;
        text-transform: uppercase;
        box-shadow: inset 0 0 0 1px rgba(8,112,82,.08);
    }

    .place-title {
        min-height: 2.65rem;
        color: var(--gallery-ink);
        font-size: 1.05rem;
        font-weight: 900;
        line-height: 1.3;
        margin: .85rem 0 .35rem;
    }

    .rating {
        color: #8a4b16;
        font-weight: 850;
    }

    .detail-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        min-height: 38px;
        padding: .55rem .85rem;
        border-radius: 8px;
        background: var(--gallery-primary);
        color: #fff;
        font-weight: 850;
        text-decoration: none;
        transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .detail-link:hover {
        background: #06457e;
        color: #fff;
        transform: translateX(3px);
        box-shadow: 0 12px 24px rgba(7,84,155,.24);
    }

    .video-card {
        position: relative;
        min-height: 320px;
        width: 100%;
        aspect-ratio: 19 / 6;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
        border-radius: 22px;
        color: #fff;
        background: linear-gradient(180deg, #17314c, #0d1b2a);
        box-shadow: 0 18px 44px rgba(18, 36, 62, .16);
        border: 1px solid rgba(255,255,255,.08);
        scroll-snap-align: start;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .video-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 24px 56px rgba(18, 36, 62, .2);
    }

    .video-card img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .25s ease;
    }

    .video-card:hover img {
        transform: scale(1.04);
    }

    .video-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 15, 24, .08), rgba(8, 15, 24, .82));
    }

    .video-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 1rem 1rem 1.05rem;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        min-height: 100%;
        gap: .35rem;
    }

    .video-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: .55rem;
    }

    .play-button {
        width: 54px;
        height: 54px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: rgba(255,255,255,.95);
        color: var(--gallery-primary);
        font-size: 1.35rem;
        box-shadow: 0 12px 24px rgba(0,0,0,.18);
        flex: 0 0 auto;
    }

    .video-chip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .28rem .55rem;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        color: #fff;
        font-size: .68rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .08em;
        white-space: nowrap;
        backdrop-filter: blur(6px);
    }

    .video-title {
        font-size: 1.07rem;
        font-weight: 900;
        line-height: 1.25;
        margin: 0 0 .18rem;
    }

    .video-caption {
        color: rgba(255,255,255,.86);
        font-size: .9rem;
        line-height: 1.45;
        margin: 0;
        max-width: 100%;
    }

    .wi-video-section {
        position: relative;
        overflow: hidden;
        background: #fff;
    }

    .wi-video-head {
        text-align: center;
        margin-bottom: 3.35rem;
    }

    .wi-video-kicker {
        color: #34405a;
        font-size: .78rem;
        font-weight: 900;
        letter-spacing: .26em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .wi-video-title {
        display: grid;
        grid-template-columns: minmax(80px, 1fr) auto minmax(80px, 1fr);
        align-items: center;
        gap: clamp(1.5rem, 4vw, 4.5rem);
        color: #07164c;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: clamp(2.15rem, 5vw, 4.15rem);
        font-weight: 700;
        line-height: 1.05;
        letter-spacing: 0;
        margin: 0;
    }

    .wi-video-title::before,
    .wi-video-title::after {
        content: "";
        height: 4px;
        background: #07164c;
    }

    .wi-video-shell {
        position: relative;
        display: grid;
        grid-template-columns: minmax(180px, 1fr) minmax(420px, 1056px) minmax(180px, 1fr);
        align-items: center;
        gap: 30px;
        width: min(1380px, calc(100vw + 260px));
        margin-left: 50%;
        transform: translateX(-50%);
    }

    .wi-video-main,
    .wi-video-peek {
        position: relative;
        overflow: hidden;
        background: #111827;
    }

    .wi-video-main {
        aspect-ratio: 16 / 8.45;
        border-radius: 8px;
        box-shadow: 0 24px 58px rgba(7, 22, 76, .16);
        transition: transform .26s ease, box-shadow .26s ease;
    }

    .wi-video-main:hover {
        transform: translateY(-4px);
        box-shadow: 0 32px 72px rgba(7, 22, 76, .22);
    }

    .wi-video-main.is-swapping img {
        animation: videoSwap .34s ease both;
    }

    .wi-video-peek.is-swapping img {
        animation: videoSwap .34s ease both;
    }

    .wi-video-main::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        height: 16px;
        background: #000;
        z-index: 2;
    }

    .wi-video-peek {
        aspect-ratio: 16 / 9.3;
        opacity: .86;
        filter: saturate(.9);
    }

    .wi-video-peek.left {
        justify-self: end;
        width: min(350px, 100%);
    }

    .wi-video-peek.right {
        justify-self: start;
        width: min(365px, 100%);
    }

    .wi-video-main img,
    .wi-video-peek img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .3s ease, filter .3s ease;
    }

    .wi-video-main:hover img {
        transform: scale(1.025);
        filter: saturate(1.06);
    }

    .wi-video-main::after,
    .wi-video-peek::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,.04), rgba(0,0,0,.2));
    }

    .wi-video-play {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 3;
        width: 62px;
        height: 62px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, .72);
        color: #fff;
        font-size: 1.55rem;
        text-decoration: none;
    }

    .wi-video-play:hover {
        color: #fff;
        background: rgba(7, 84, 155, .92);
    }

    .wi-video-nav {
        position: absolute;
        top: 50%;
        z-index: 6;
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 50%;
        background: #fff;
        color: #1f2937;
        font-size: 1.35rem;
        box-shadow: 0 12px 28px rgba(17, 29, 50, .16);
        transform: translateY(-50%);
        transition: transform .2s ease, background .2s ease, color .2s ease, box-shadow .2s ease;
    }

    .wi-video-nav:hover {
        background: var(--gallery-primary);
        color: #fff;
        box-shadow: 0 16px 34px rgba(7,84,155,.24);
    }

    .wi-video-nav.prev {
        left: calc(50% - min(528px, 38vw) - 58px);
    }

    .wi-video-nav.prev:hover {
        transform: translate(-4px, -50%);
    }

    .wi-video-nav.next {
        right: calc(50% - min(528px, 38vw) - 58px);
    }

    .wi-video-nav.next:hover {
        transform: translate(4px, -50%);
    }

    .wi-video-caption {
        text-align: center;
        margin-top: 1.8rem;
    }

    .wi-video-caption h3 {
        color: #07164c;
        font-size: clamp(1.45rem, 2.5vw, 2rem);
        font-weight: 900;
        line-height: 1.25;
        margin-bottom: .65rem;
    }

    .wi-video-location {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        color: #07164c;
        font-weight: 650;
    }

    .wi-video-location i {
        color: #e3342f;
    }

    .cta-gallery {
        padding: 5rem 0 6rem;
        background: #fff;
    }

    .cta-panel {
        overflow: hidden;
        border-radius: 8px;
        color: #fff;
        text-align: center;
        padding: clamp(2.4rem, 6vw, 4.5rem);
        background:
            linear-gradient(90deg, rgba(7, 54, 100, .98), rgba(7, 84, 155, .92)),
            url('https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1600&q=80') center/cover;
        box-shadow: 0 26px 62px rgba(7, 84, 155, .18);
    }

    .cta-panel p {
        max-width: 760px;
        margin: 1rem auto 1.8rem;
        color: rgba(255,255,255,.78);
        line-height: 1.75;
    }

    .btn-map {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        min-height: 50px;
        padding: .85rem 1.35rem;
        border-radius: 8px;
        background: var(--gallery-mint);
        color: #063f35;
        font-weight: 900;
        text-decoration: none;
    }

    .btn-map:hover {
        color: #063f35;
        background: #72f5d0;
    }

    .gallery-footer {
        border-top: 1px solid #cfd7df;
        background: #e9ecef;
        color: #667085;
        padding: 2rem 0;
    }

    .footer-brand {
        color: var(--gallery-primary);
        font-weight: 900;
        margin-bottom: .55rem;
    }

    .footer-link {
        color: #667085;
        text-decoration: none;
        font-weight: 650;
    }

    .footer-link:hover {
        color: var(--gallery-primary);
    }

    @media (max-width: 575.98px) {
        .gallery-hero {
            min-height: 680px;
        }

        .gallery-section,
        .cta-gallery {
            padding-block: 4rem;
        }

        .horizontal-track {
            grid-auto-columns: minmax(280px, 88vw);
            gap: .85rem;
        }

        .video-card {
            min-height: 280px;
            aspect-ratio: 16 / 9;
            border-radius: 18px;
        }

        .wi-video-head {
            margin-bottom: 2rem;
        }

        .wi-video-title {
            grid-template-columns: 1fr;
            gap: .9rem;
        }

        .wi-video-title::before,
        .wi-video-title::after {
            width: 72px;
            margin-inline: auto;
        }

        .wi-video-shell {
            grid-template-columns: 1fr;
            width: 100%;
            gap: 0;
            margin-left: 0;
            transform: none;
        }

        .wi-video-peek {
            display: none;
        }

        .wi-video-main {
            aspect-ratio: 16 / 9;
        }

        .wi-video-nav {
            width: 42px;
            height: 42px;
        }

        .wi-video-nav.prev {
            left: 10px;
        }

        .wi-video-nav.next {
            right: 10px;
        }

        .btn-map {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $destinations = [
        [
            'name' => 'Puncak Mas',
            'category' => 'Wisata Alam',
            'rating' => '4.9',
            'image' => 'https://salsawisata.com/wp-content/uploads/2022/09/Puncak-Mas-Lampung.jpg',
            'fallback' => 'https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Bukit Sakura',
            'category' => 'Spot Foto',
            'rating' => '4.8',
            'image' => 'https://www.rumah123.com/seo-cms/assets/Rumah_rumah_Jepang_di_Bukit_Sakura_Kemiling_Lampung_59db625a6f/Rumah_rumah_Jepang_di_Bukit_Sakura_Kemiling_Lampung_59db625a6f.jpg',
            'fallback' => 'https://images.unsplash.com/photo-1522383225653-ed111181a951?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Lembah Hijau',
            'category' => 'Rekreasi Keluarga',
            'rating' => '4.7',
            'image' => 'https://www.indonesia.travel/contentassets/54c9cff29b774a51bb13ab2f56ec25bd/lembah_hijau_banner.jpg',
            'fallback' => 'https://images.unsplash.com/photo-1472396961693-142e6e269027?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Wira Garden',
            'category' => 'Wisata Alam',
            'rating' => '4.7',
            'image' => 'https://salsawisata.com/wp-content/uploads/2022/11/Wira-Garden-Bandar-Lampung.jpg',
            'fallback' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Pantai Mutun',
            'category' => 'Wisata Pantai',
            'rating' => '4.8',
            'image' => 'https://www.itrip.id/wp-content/uploads/2021/04/Pantai-Mutun-Lampung.jpg',
            'fallback' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Pantai Duta Wisata',
            'category' => 'Wisata Pantai',
            'rating' => '4.6',
            'image' => 'https://www.direktori-wisata.com/wp-content/uploads/2018/12/Pantai-Duta-Wisata-Lampung.jpg',
            'fallback' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=900&q=80',
        ],
    ];

    $videos = [
        ['title' => 'Explore Puncak Mas Bandar Lampung', 'platform' => 'YouTube', 'tag' => 'Wisata Alam', 'location' => 'Puncak Mas', 'caption' => 'Pemandangan bukit, jalan wisata, dan suasana alam yang cocok untuk inspirasi perjalanan.', 'url' => 'https://youtu.be/8ltC9b4JGiw?si=8fhksh5vRyeBdTNC', 'image' => 'https://salsawisata.com/wp-content/uploads/2022/09/Puncak-Mas-Lampung.jpg'],
        ['title' => 'Menikmati Suasana Pantai Mutun', 'platform' => 'YouTube', 'tag' => 'Pantai', 'location' => 'Pantai Mutun', 'caption' => 'Momen tepi laut, panorama pantai, dan suasana liburan yang hangat untuk referensi wisata.', 'url' => 'https://youtu.be/40_gBg9VwOc?si=I_WfMbB2uuxiu_Uj', 'image' => 'https://www.itrip.id/wp-content/uploads/2021/04/Pantai-Mutun-Lampung.jpg'],
        ['title' => 'Bukit Sakura dari Ketinggian', 'platform' => 'YouTube', 'tag' => 'Spot Foto', 'location' => 'Bukit Sakura', 'caption' => 'Sudut terbaik untuk foto, videografi, dan rekomendasi destinasi favorit di Bandar Lampung.', 'url' => 'https://youtu.be/Vel1GdinyZA?si=jEWKjaN__MODFR_m', 'image' => 'https://www.rumah123.com/seo-cms/assets/Rumah_rumah_Jepang_di_Bukit_Sakura_Kemiling_Lampung_59db625a6f/Rumah_rumah_Jepang_di_Bukit_Sakura_Kemiling_Lampung_59db625a6f.jpg'],
        ['title' => 'Wisata Alam Bandar Lampung', 'platform' => 'YouTube', 'tag' => 'Travel Vlog', 'location' => 'Bandar Lampung', 'caption' => 'Kumpulan momen perjalanan dan spot unggulan yang siap menjadi referensi liburan berikutnya.', 'url' => 'https://youtu.be/lzgD_HIoVic?si=IzUTeo636zVNXaL4', 'image' => 'https://www.indonesia.travel/contentassets/54c9cff29b774a51bb13ab2f56ec25bd/lembah_hijau_banner.jpg'],
        ['title' => 'Highlights Wisata Pilihan BalamGo', 'platform' => 'YouTube', 'tag' => 'Rekomendasi', 'location' => 'Bandar Lampung', 'caption' => 'Video pilihan untuk mengenalkan destinasi menarik dan pengalaman wisata di sekitar kota.', 'url' => 'https://youtu.be/c1EZqwYRemg?si=QiQ957nj6onKY9DN', 'image' => 'https://salsawisata.com/wp-content/uploads/2022/11/Wira-Garden-Bandar-Lampung.jpg'],
    ];

    $videos = array_slice($videos, 0, 5);

    $instagramPosts = [
        ['title' => 'Momen Wisata Kota', 'caption' => 'Cuplikan seru dari destinasi pilihan.', 'image' => 'https://salsawisata.com/wp-content/uploads/2022/09/Puncak-Mas-Lampung.jpg', 'url' => 'https://www.instagram.com/p/DZkGgJaEwVK/?igsh=NnhlZHI4aHg3eTZi'],
        ['title' => 'Jelajah Bandar Lampung', 'caption' => 'Inspirasi singkat untuk rencana liburan.', 'image' => 'https://www.itrip.id/wp-content/uploads/2021/04/Pantai-Mutun-Lampung.jpg', 'url' => 'https://www.instagram.com/p/C7JeMwzvyQT/?igsh=MWhmdGgyajliNDBkNA=='],
        ['title' => 'Spot Foto Favorit', 'caption' => 'Referensi tempat cantik untuk dikunjungi.', 'image' => 'https://www.rumah123.com/seo-cms/assets/Rumah_rumah_Jepang_di_Bukit_Sakura_Kemiling_Lampung_59db625a6f/Rumah_rumah_Jepang_di_Bukit_Sakura_Kemiling_Lampung_59db625a6f.jpg', 'url' => 'https://www.instagram.com/p/CZLETnkvSmq/?igsh=MXE5Y203eHlteXIxMQ=='],
        ['title' => 'Cerita Destinasi', 'caption' => 'Potret perjalanan dan suasana wisata.', 'image' => 'https://www.indonesia.travel/contentassets/54c9cff29b774a51bb13ab2f56ec25bd/lembah_hijau_banner.jpg', 'url' => 'https://www.instagram.com/p/C7VvqZxPeo3/?igsh=MXhnMWF4OHY3dGt0bw=='],
    ];
@endphp

<main>
    <section class="gallery-hero">
        <div class="container">
            <div class="gallery-hero-content">
                <div class="gallery-kicker">
                    <i class="bi bi-images"></i>
                    Hero Galeri
                </div>
                <h1 class="gallery-title">Galeri BalamGo</h1>
                <p class="gallery-copy">
                    Sebelum memulai perjalanan, kenali terlebih dahulu pesona Bandar Lampung melalui koleksi destinasi pilihan, video perjalanan, dan rekomendasi wisata yang telah kami siapkan untukmu.
                </p>
            </div>
        </div>
    </section>

    <section class="gallery-section soft">
        <div class="container">
            <div class="row align-items-end g-3 mb-4">
                <div class="col-lg-8">
                    <div class="section-kicker">Rekomendasi Destinasi</div>
                    <h2 class="section-heading">Destinasi Favorit Wisatawan</h2>
                </div>
            </div>

            <div class="horizontal-track">
                @foreach($destinations as $item)
                    <article class="gallery-card gallery-reveal">
                        <div class="card-image">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" onerror="this.onerror=null; this.src='{{ $item['fallback'] }}';">
                        </div>
                        <div class="card-body-space">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <span class="category-pill">{{ $item['category'] }}</span>
                                <span class="rating"><i class="bi bi-star-fill"></i> {{ $item['rating'] }}</span>
                            </div>
                            <h3 class="place-title">{{ $item['name'] }}</h3>
                            <a href="{{ route('peta') }}" class="detail-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="gallery-section wi-video-section">
        <div class="container">
            <div class="wi-video-head">
                <div class="wi-video-kicker">Galeri Video</div>
                <h2 class="wi-video-title"><span>Jelajahi Keindahan Destinasi</span></h2>
            </div>
        </div>

        <div class="wi-video-shell" data-wi-video>
            <button class="wi-video-nav prev" type="button" data-wi-video-prev aria-label="Video sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="wi-video-peek left" aria-hidden="true">
                <img data-wi-video-left src="{{ $videos[count($videos) - 1]['image'] }}" alt="">
            </div>

            <article class="wi-video-main">
                <img data-wi-video-main src="{{ $videos[0]['image'] }}" alt="{{ $videos[0]['title'] }}">
                <a data-wi-video-link href="{{ $videos[0]['url'] }}" target="_blank" rel="noopener noreferrer" class="wi-video-play" aria-label="Putar video {{ $videos[0]['title'] }}">
                    <i class="bi bi-play-fill"></i>
                </a>
            </article>

            <div class="wi-video-peek right" aria-hidden="true">
                <img data-wi-video-right src="{{ $videos[1]['image'] }}" alt="">
            </div>

            <button class="wi-video-nav next" type="button" data-wi-video-next aria-label="Video berikutnya">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="container">
            <div class="wi-video-caption">
                <h3 data-wi-video-title>{{ $videos[0]['title'] }}</h3>
                <div class="wi-video-location">
                    <i class="bi bi-geo-alt"></i>
                    <span data-wi-video-location>{{ $videos[0]['location'] }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-section soft">
        <div class="container">
            <div class="row align-items-end g-3 mb-4">
                <div class="col-lg-8">
                    <div class="section-kicker">Postingan Instagram Kami</div>
                    <h2 class="section-heading">Galeri Sosial Media yang Bisa Langsung Dihubungkan</h2>
                    <p class="section-copy">Ikuti pilihan postingan sosial media kami untuk melihat cuplikan destinasi, suasana perjalanan, dan inspirasi wisata terbaru.</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach($instagramPosts as $post)
                    <div class="col-md-6 col-xl-3">
                        <article class="instagram-card gallery-reveal">
                            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}">
                            <div class="instagram-card-body">
                                <span class="instagram-badge"><i class="bi bi-instagram"></i> Instagram</span>
                                <h3 class="place-title" style="min-height: auto; margin-top: 0;">{{ $post['title'] }}</h3>
                                <p class="section-copy" style="margin-top: .35rem;">{{ $post['caption'] }}</p>
                                <a href="{{ $post['url'] ?? 'https://www.instagram.com/' }}" target="_blank" rel="noopener noreferrer" class="instagram-link">
                                    Lihat postingan
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cta-gallery">
        <div class="container">
            <div class="cta-panel">
                <div class="section-kicker text-white justify-content-center">Siap Menjelajah?</div>
                <h2 class="section-heading text-white">Lihat Seluruh Destinasi Melalui Peta Interaktif</h2>
                <p>
                    Gunakan fitur peta interaktif BalamGo untuk menemukan lokasi wisata, melihat informasi lengkap destinasi, dan mengetahui fasilitas keselamatan di sekitarnya.
                </p>
                <a href="{{ route('peta') }}" class="btn-map">
                    <i class="bi bi-map"></i>
                    Buka Interactive Map
                </a>
            </div>
        </div>
    </section>
</main>

<script>
    (function () {
        const cards = document.querySelectorAll('.gallery-reveal');
        if (!cards.length) return;

        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            cards.forEach((card) => card.classList.add('in'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('in');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });

        cards.forEach((card, index) => {
            card.style.transitionDelay = `${Math.min(index % 4, 3) * 80}ms`;
            observer.observe(card);
        });
    })();

    (function () {
        const videos = @json($videos);
        const root = document.querySelector('[data-wi-video]');
        if (!root || !videos.length) return;

        let active = 0;
        const mainImage = root.querySelector('[data-wi-video-main]');
        const leftImage = root.querySelector('[data-wi-video-left]');
        const rightImage = root.querySelector('[data-wi-video-right]');
        const link = root.querySelector('[data-wi-video-link]');
        const title = document.querySelector('[data-wi-video-title]');
        const location = document.querySelector('[data-wi-video-location]');

        function getIndex(offset) {
            return (active + offset + videos.length) % videos.length;
        }

        function renderVideo() {
            const current = videos[active];
            const previous = videos[getIndex(-1)];
            const next = videos[getIndex(1)];

            [mainImage.parentElement, leftImage.parentElement, rightImage.parentElement].forEach((item) => {
                item.classList.remove('is-swapping');
                void item.offsetWidth;
                item.classList.add('is-swapping');
            });

            mainImage.src = current.image;
            mainImage.alt = current.title;
            leftImage.src = previous.image;
            rightImage.src = next.image;
            link.href = current.url || '#';
            link.setAttribute('aria-label', 'Putar video ' + current.title);
            title.textContent = current.title;
            location.textContent = current.location || 'Bandar Lampung';
        }

        root.querySelector('[data-wi-video-prev]').addEventListener('click', function () {
            active = getIndex(-1);
            renderVideo();
        });

        root.querySelector('[data-wi-video-next]').addEventListener('click', function () {
            active = getIndex(1);
            renderVideo();
        });
    })();
</script>

<footer class="gallery-footer">
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
