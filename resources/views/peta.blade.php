@extends('layouts.app')

@section('title', 'Peta Interaktif BalamGo')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
<style>
    /* MarkerCluster CSS (Vite/NPM bundling tidak otomatis, jadi pakai inline CDN file jika sudah ada)
       Untuk saat ini tetap gunakan style lokal dari leaflet.markercluster */

    :root {
        --map-primary: #1565c8;
        --map-primary-dark: #0b4ea2;
        --map-ink: #172033;
        --map-muted: #5f6b7c;
        --map-border: #dfe6ee;
        --map-soft: #f4f7fb;
        --map-surface: #ffffff;
        --map-surface-2: #edf4fb;
        --map-shadow: 0 18px 40px rgba(15, 23, 42, .10);
        --map-danger: #ef4e45;
    }

    body {
        overflow: hidden;
        background:
            radial-gradient(circle at top, #f8fbff 0%, #f3f7fb 45%, #edf2f7 100%);
        color: var(--map-ink);
        font-family: Inter, "Segoe UI", Arial, sans-serif;
    }

    .map-shell {
        height: calc(100vh - 57px);
        display: grid;
        grid-template-columns: 380px minmax(0, 1fr);
        border-top: 1px solid var(--map-border);
        transition: grid-template-columns .24s ease;
        background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(245,248,252,.98));
    }


    .map-shell.sidebar-closed {
        grid-template-columns: 0 minmax(0, 1fr);
    }

    /* Sidebar proporsional (desktop) */
    .map-shell:not(.sidebar-closed) .map-sidebar {
        width: 380px;
        max-width: 380px;
    }

    .map-shell.sidebar-closed .map-sidebar {
        max-width: 0;
        width: 0;
    }


    .map-sidebar {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        min-height: 0;
        min-width: 0;
        width: 360px;
        max-width: 380px;
        background: linear-gradient(180deg, rgba(255,255,255,.97), rgba(247,250,253,.98));

        border-right: 1px solid var(--map-border);
        box-shadow: 14px 0 32px rgba(15, 23, 42, .08);
        transition: opacity .2s ease, transform .24s ease;
    }

    .map-shell.sidebar-closed .map-sidebar {
        width: 0;
        opacity: 0;
        transform: translateX(-18px);
        pointer-events: none;
        overflow: hidden;
    }

    .map-shell:not(.sidebar-closed) .map-sidebar {
        width: 380px;
        opacity: 1;
        pointer-events: auto;
    }

    .sidebar-scroll {
        flex: 1;
        overflow: auto;
        padding: 14px 14px 18px;
    }

    .sidebar-intro {
        padding: 12px;
        border: 1px solid var(--map-border);
        border-radius: 18px;
        background: linear-gradient(135deg, #ffffff 0%, #f3f7fd 100%);
        box-shadow: var(--map-shadow);
        margin-bottom: 12px;
    }

    .sidebar-intro-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
    }

    .sidebar-intro .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--map-primary);
        font-size: .75rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .14em;
        margin: 0;
    }

    .sidebar-intro h3 {
        margin: 0 0 6px;
        color: #172033;
        font-size: 1.08rem;
        font-weight: 900;
        line-height: 1.35;
    }

    .sidebar-intro p {
        margin: 0;
        color: var(--map-muted);
        font-size: .92rem;
        line-height: 1.55;
    }

    .sidebar-collapse {
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        border: 1px solid rgba(21,101,200,.18);
        border-radius: 12px;
        background: rgba(21,101,200,.08);
        color: var(--map-primary-dark);
        box-shadow: none;
        transition: background .18s ease, color .18s ease, border-color .18s ease;
    }

    .sidebar-collapse:hover {
        background: var(--map-primary);
        border-color: var(--map-primary);
        color: #fff;
    }

    .sidebar-open {
        position: absolute;
        top: 18px;
        left: 18px;
        z-index: 900;
        width: 44px;
        height: 50px;
        display: none;
        place-items: center;
        border: 1px solid var(--map-border);
        border-radius: 14px;
        background: rgba(255,255,255,.94);
        color: #34405a;
        box-shadow: 0 10px 24px rgba(20, 38, 64, .12);
    }

    .map-shell.sidebar-closed .sidebar-open {
        display: grid;
    }

    .map-shell:not(.sidebar-closed) .sidebar-open {
        display: none;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 58px;
        padding: 0 16px;
        border: 1px solid var(--map-border);
        border-radius: 14px;
        background: linear-gradient(180deg, #fff, #f5f8fc);
        color: #536071;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.9), 0 10px 24px rgba(15, 23, 42, .06);
    }

    .search-box i {
        font-size: 1.25rem;
    }

    .search-box input {
        width: 100%;
        border: 0;
        outline: 0;
        background: transparent;
        color: var(--map-ink);
        font-weight: 650;
    }

    .search-box input::placeholder {
        color: #747f8f;
    }

    .side-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 26px 0 14px;
    }

    .side-title {
        color: #0b4ea2;
        font-size: .84rem;
        font-weight: 900;
        letter-spacing: .13em;
        text-transform: uppercase;
        margin: 0;
    }

    .side-see-all {
        border: 0;
        background: transparent;
        color: var(--map-primary);
        font-size: .8rem;
        font-weight: 850;
        padding: 0;
    }

    .layer-switches {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .chip-row {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 2px;
        pointer-events: auto;
        scrollbar-width: thin;
    }

    .mini-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--map-border);
        border-radius: 999px;
        padding: 7px 10px;
        background: rgba(255,255,255,.92);
        color: #334155;
        font-size: .78rem;
        font-weight: 800;
        box-shadow: 0 8px 18px rgba(15, 23, 42, .06);
    }

    .mini-chip.active {
        background: linear-gradient(135deg, #0f5dbf, #1d70d8);
        color: #fff;
        border-color: transparent;
    }

    .map-topbar {
        position: absolute;
        top: 18px;
        left: 18px;
        right: 18px;
        z-index: 850;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: auto;
        max-width: none;
        pointer-events: none;
        transform: none;
    }

    .map-topbar > .map-search-card {
        width: 100%;
        max-width: none;
        margin-left: 0;
    }

    .map-topbar > .chip-row {
        width: 100%;
        max-width: none;
        margin-left: 0;
        justify-content: flex-start;
    }

    .map-topbar > * {
        pointer-events: auto;
    }

    .map-shell.sidebar-closed .map-topbar {
        left: 78px;
        width: min(920px, calc(100% - 156px)) !important;
    }

    .map-shell.sidebar-closed .layer-tabs {
        left: 50%;
        transform: translateX(-50%);
    }

    .map-search-card {
        pointer-events: auto;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 18px;
        background: rgba(255,255,255,.94);
        border: 1px solid rgba(226,232,240,.9);
        box-shadow: 0 18px 36px rgba(15, 23, 42, .12);
        backdrop-filter: blur(14px);
    }

    .map-search-card i {
        color: #2b6cb0;
        font-size: 1.05rem;
    }

    .map-search-card input {
        flex: 1;
        border: 0;
        outline: 0;
        background: transparent;
        font-weight: 700;
        color: #172033;
    }

    .map-search-card input::placeholder {
        color: #5b6a7d;
        font-weight: 600;
    }

    .search-rail {
        width: min(560px, 100%);
        max-height: min(390px, calc(100vh - 220px));
        overflow: auto;
        border-radius: 18px;
        border: 1px solid rgba(226,232,240,.9);
        background: rgba(255,255,255,.96);
        box-shadow: 0 26px 50px rgba(15, 23, 42, .16);
        backdrop-filter: blur(16px);
        display: none;
        pointer-events: auto;
    }

    .search-rail.is-open {
        display: block;
    }

    .rail-head {
        display: flex;
        align-items: start;
        justify-content: space-between;
        gap: 10px;
        padding: 14px;
        border-bottom: 1px solid var(--map-border);
    }

    .rail-head .selected-close {
        position: static;
        flex: 0 0 34px;
    }

    .rail-label {
        color: #0e5db7;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .14em;
        font-weight: 900;
    }

    .rail-title {
        margin-top: 4px;
        color: #172033;
        font-size: 1rem;
        font-weight: 900;
    }

    .rail-results {
        padding: 10px;
        display: grid;
        gap: 8px;
    }

    .rail-result {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        border-radius: 14px;
        padding: 10px;
        background: linear-gradient(180deg, #fff, #f6f9fd);
        border: 1px solid var(--map-border);
        color: #172033;
        text-decoration: none;
        text-align: left;
        cursor: pointer;
    }

    .rail-result:hover {
        color: #172033;
        border-color: #c8d7e8;
    }

    .rail-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: #fff;
        flex: 0 0 38px;
    }

    .rail-copy strong {
        display: block;
        font-size: .92rem;
        line-height: 1.2;
    }

    .rail-copy span {
        color: #5b6a7d;
        font-size: .78rem;
    }

    .layer-check,
    .category-tile,
    .mitigation-pill,
    .legend-card {
        border: 1px solid var(--map-border);
        background: #fff;
        box-shadow: 0 10px 24px rgba(27,46,73,.05);
    }

    .layer-check {
        min-height: 54px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-radius: 14px;
        padding: 12px 13px;
        color: #253044;
        font-weight: 850;
        cursor: pointer;
        background: linear-gradient(180deg, #fff, #f7fafd);
        box-shadow: 0 10px 22px rgba(15, 23, 42, .06);
    }

    .layer-check input {
        width: 22px;
        height: 22px;
        accent-color: var(--map-primary);
    }

    .mini-pin {
        width: 28px;
        height: 28px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        color: #fff;
        font-size: 1rem;
    }

    .layer-dot {
        width: 12px;
        height: 12px;
        flex: 0 0 12px;
        border-radius: 50%;
    }

    .category-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .category-tile {
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 10px;
        color: #273244;
        font-size: .76rem;
        font-weight: 850;
        cursor: pointer;
        text-align: center;
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        background: linear-gradient(180deg, #fff, #f7fafd);
    }

    .category-tile input,
    .mitigation-pill input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .category-tile i {
        display: none;
    }

    .category-color {
        width: 28px;
        height: 6px;
        border-radius: 999px;
    }

    .category-tile:has(input:checked),
    .mitigation-pill:has(input:checked) {
        border-color: rgba(21,101,200,.45);
        box-shadow: 0 14px 30px rgba(21,101,200,.12);
    }

    .category-tile:hover,
    .mitigation-pill:hover,
    .nearby-card:hover {
        transform: translateY(-3px);
    }

    .mitigation-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mitigation-pill {
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 10px;
        color: #253044;
        font-size: .76rem;
        font-weight: 850;
        cursor: pointer;
        background: linear-gradient(180deg, #fff, #f7fafd);
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .layer-info-button {
        min-height: 40px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--map-border);
        border-radius: 999px;
        padding: 9px 12px;
        background: linear-gradient(180deg, #fff, #f7fafd);
        color: #253044;
        font-size: .8rem;
        font-weight: 900;
        box-shadow: 0 10px 22px rgba(15, 23, 42, .06);
        cursor: pointer;
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .layer-info-button:hover {
        transform: translateY(-2px);
        border-color: rgba(21,101,200,.36);
        box-shadow: 0 14px 28px rgba(21,101,200,.12);
    }

    .legend-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        overflow: hidden;
        border-radius: 9px;
    }

    .legend-card {
        min-height: 52px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 0;
        color: #34405a;
        font-weight: 750;
    }

    .legend-card:nth-child(odd) {
        border-right: 0;
    }

    .sidebar-footer {
        padding: 14px 22px 20px;
        border-top: 1px solid var(--map-border);
        background: #fff;
    }

    .danger-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 52px;
        width: 100%;
        border: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #ff5b58, #e23d3a);
        color: #fff;
        font-weight: 900;
        box-shadow: 0 16px 30px rgba(239, 78, 69, .24);
        letter-spacing: .02em;
    }

    .map-stage {
        position: relative;
        min-width: 0;
        min-height: 0;
        background: #dfe7ef;
    }

    #map {
        height: 100%;
        width: 100%;
        background: #dfe7ef;
    }

    .layer-tabs {
        position: absolute;
        top: 152px;
        left: 50%;
        z-index: 800;
        display: flex;
        align-items: center;
        gap: 6px;
        width: min(420px, calc(100% - 24px));
        max-width: 440px;
        padding: 6px;
        border-radius: 999px;
        background: rgba(255,255,255,.9);
        border: 1px solid rgba(226,232,240,.85);
        box-shadow: 0 14px 34px rgba(17, 29, 50, .13);
        backdrop-filter: blur(12px);
        transform: translateX(-50%);
        transition: left .22s ease;
    }

    .map-shell.sidebar-closed .layer-tabs {
        left: 50%;
    }

    .layer-tab {
        flex: 1 1 0;
        min-width: 0;
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 0;
        border-radius: 999px;
        padding: 10px 18px;
        color: #536071;
        font-weight: 900;
        background: transparent;
        transition: background .18s ease, color .18s ease;
    }

    .layer-tab.active {
        color: #fff;
        background: linear-gradient(180deg, #1d70d8, #0f5dbf);
        box-shadow: 0 10px 22px rgba(21,101,200,.25);
    }

    .floating-map-btn {
        position: absolute;
        right: 12px;
        z-index: 760;
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        border: 1px solid var(--map-border);
        border-radius: 12px;
        background: rgba(255,255,255,.94);
        color: #263140;
        box-shadow: 0 12px 28px rgba(17, 29, 50, .12);
    }

    .locate-btn {
        top: 112px;
    }

    .map-marker {
        position: relative;
        width: 36px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 19px 19px 19px 5px;
        color: #fff;
        border: 3px solid #fff;
        box-shadow: 0 9px 22px rgba(16, 32, 54, .28);
        transform: rotate(-45deg);
    }

    .map-marker::after {
        content: "";
        position: absolute;
        inset: -12px;
        z-index: -1;
        border-radius: 50%;
        background: currentColor;
        opacity: .14;
        transform: rotate(45deg);
    }

    .map-marker i {
        font-size: 16px;
        transform: rotate(45deg);
    }

    .cluster-marker {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        border: 2px solid #fff;
        background: linear-gradient(135deg, #0f5dbf, #1d70d8);
        color: #fff;
        box-shadow: 0 14px 26px rgba(15, 23, 42, .18);
        font-weight: 900;
        font-size: .82rem;
    }

.leaflet-popup-content-wrapper,
    .leaflet-popup-tip {
        display: none;
    }


    .leaflet-control-zoom {
        margin-top: 12px !important;
        margin-right: 12px !important;
        border: 0 !important;
        border-radius: 13px !important;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(17,29,50,.14) !important;
    }

    .leaflet-control-zoom a {
        width: 48px !important;
        height: 42px !important;
        display: grid !important;
        place-items: center;
        border: 0 !important;
        color: #111827 !important;
        font-weight: 900;
    }

    .selected-card {
        position: absolute;
        top: 74px;
        right: 12px;
        z-index: 860;
        width: min(340px, calc(100vw - 24px));
        overflow: hidden;
        border-radius: 18px;
        background: linear-gradient(180deg, #fff 0%, #f6faff 100%);
        border: 1px solid rgba(220, 227, 234, .95);
        box-shadow: 0 24px 58px rgba(16, 31, 52, .18);
    }

    .layer-detail-popup {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 870;
        width: min(520px, calc(100% - 32px));
        max-height: min(620px, calc(100% - 150px));
        overflow: hidden;
        border-radius: 18px;
        border: 1px solid rgba(220, 227, 234, .95);
        background: linear-gradient(180deg, #fff 0%, #f6faff 100%);
        box-shadow: 0 28px 70px rgba(16, 31, 52, .24);
        transform: translate(-50%, -50%);
    }

    .layer-detail-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 16px 12px;
        border-bottom: 1px solid var(--map-border);
    }

    .layer-detail-title {
        margin: 8px 0 0;
        color: #162033;
        font-size: 1.18rem;
        font-weight: 900;
    }

    .layer-detail-desc {
        margin: 8px 0 0;
        color: #5f6b7c;
        font-size: .9rem;
        line-height: 1.5;
    }

    .layer-detail-list {
        max-height: 360px;
        overflow: auto;
        padding: 10px 16px 16px;
        display: grid;
        gap: 8px;
    }

    .layer-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        border: 1px solid var(--map-border);
        border-radius: 12px;
        background: #fff;
        color: #253044;
    }

    .layer-detail-item strong {
        display: block;
        font-size: .9rem;
        line-height: 1.25;
    }

    .layer-detail-item span:last-child {
        color: #64748b;
        font-size: .78rem;
        font-weight: 700;
    }

    .selected-image {
        height: 150px;
        background: linear-gradient(135deg, #b5ebe1, #6ab7df);
        position: relative;
        overflow: hidden;
    }

    .selected-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .selected-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border: 1px solid rgba(148, 163, 184, .35);
        border-radius: 50%;
        background: rgba(255,255,255,.95);
        color: #1f2937;
        box-shadow: 0 8px 18px rgba(15, 23, 42, .12);
        cursor: pointer;
    }

    .selected-close:hover {
        background: #fff;
        color: #0f172a;
    }

    .selected-body {
        padding: 16px;
    }

    .type-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 24px;
        padding: 3px 9px;
        border-radius: 999px;
        background: #e9f5ff;
        color: #07549b;
        font-size: .72rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .selected-title {
        margin: 10px 0 6px;
        font-size: 1.2rem;
        font-weight: 850;
        color: #162033;
    }

    .selected-meta {
        color: #687384;
        font-size: .92rem;
        line-height: 1.5;
    }

    .selected-facts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin: 14px 0;
    }

    .selected-fact {
        padding: 10px;
        border-radius: 8px;
        background: #f5f7f9;
        color: #4a5565;
        font-size: .78rem;
        font-weight: 700;
    }

    .selected-fact strong {
        display: block;
        color: #162033;
        font-size: .95rem;
        margin-top: 3px;
    }

    .selected-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 12px;
    }

    .detail-btn,
    .route-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        font-weight: 850;
        font-size: .88rem;
    }

    .detail-btn {
        background: linear-gradient(135deg, #1d70d8, #0f5dbf);
    }

    .route-btn {
        background: linear-gradient(135deg, #0f766e, #14b8a6);
    }

    .detail-btn:hover,
    .route-btn:hover {
        color: #fff;
        filter: saturate(1.05);
    }

    .nearby-panel {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 700;
        padding: 18px 18px 18px;
        border-top: 1px solid var(--map-border);
        border-radius: 24px 24px 0 0;
        background: linear-gradient(180deg, rgba(255,255,255,.95) 0%, rgba(249,251,254,.98) 100%);
        backdrop-filter: blur(14px);
        box-shadow: 0 -18px 36px rgba(15, 23, 42, .10);
    }

    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .panel-title {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #172033;
        font-size: 1.12rem;
        font-weight: 900;
    }

    .panel-title i {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #edf5ff;
        color: var(--map-primary);
    }

    .panel-see-all {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: var(--map-primary);
        font-weight: 900;
        text-decoration: none;
    }

    .nearby-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(190px, 1fr));
        gap: 18px;
    }

    .nearby-card {
        overflow: hidden;
        border-radius: 14px;
        border: 1px solid #e2e8ef;
        background: linear-gradient(180deg, #fff 0%, #f9fbfd 100%);
        box-shadow: 0 12px 28px rgba(22, 37, 61, .10);
        cursor: pointer;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .nearby-card:hover {
        box-shadow: 0 18px 36px rgba(22, 37, 61, .15);
    }

    .nearby-card img {
        width: 100%;
        height: 88px;
        object-fit: cover;
    }

    .nearby-body {
        padding: 13px 14px 14px;
    }

    .nearby-name-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .nearby-name {
        color: #172033;
        font-size: 1rem;
        font-weight: 900;
        line-height: 1.25;
    }

    .nearby-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #eaf8ef;
        color: #199653;
        font-size: .72rem;
        font-weight: 850;
        white-space: nowrap;
    }

    .nearby-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 22px;
        color: #536071;
        font-size: .88rem;
        font-weight: 800;
    }

    .nearby-row i {
        color: #536071;
    }

    @media (max-width: 1199.98px) {
        .map-shell {
            grid-template-columns: 380px minmax(0, 1fr);
        }

        .category-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .nearby-grid {
            grid-template-columns: repeat(2, minmax(190px, 1fr));
        }
    }

    @media (max-width: 991.98px) {
        body {
            overflow: auto;
        }

        /* Mobile: tampilkan peta full width sebagai tampilan awal.
           Sidebar disembunyikan default dan hanya muncul saat user menekan tombol buka sidebar. */
        .map-shell,
        .map-shell.sidebar-closed {
            height: auto;
            min-height: calc(100vh - 57px);
            grid-template-columns: 1fr;
        }

        /* sembunyikan sidebar default */
        .map-sidebar {
            display: none;
        }

        /* pastikan tombol buka sidebar terlihat */
        .sidebar-open {
            display: grid;
        }

        .map-shell:not(.sidebar-closed) .sidebar-open {
            display: none;
        }

        /* tombol collapse di dalam sidebar (saat sidebar dibuka) tetap bisa dipakai */
        .map-shell.sidebar-closed .sidebar-collapse {
            display: none;
        }

        /* saat class sidebar-closed dilepas (sidebar dibuka), tampilkan sidebar */
        .map-shell:not(.sidebar-closed) {
            grid-template-columns: 1fr;
        }
        .map-shell:not(.sidebar-closed) .map-sidebar {
            display: flex;
            position: absolute;
            left: 0;
            top: 57px; /* tinggi navbar */
            bottom: 0;
            width: min(94vw, 430px);
            max-width: min(94vw, 430px);
            z-index: 1000;
            box-shadow: 14px 0 32px rgba(15, 23, 42, .14);
        }

        .map-stage {
            height: 760px;
        }

        .selected-card {
            left: 12px;
            right: 12px;
            top: 78px;
            width: auto;
        }

        .map-shell.sidebar-closed .map-topbar,
        .map-topbar {
            left: 70px;
            right: 10px;
            width: calc(100% - 80px) !important;
        }

        .nearby-panel {
            position: relative;
        }

        .layer-tabs {
            top: 152px;
            width: calc(100% - 18px);
            justify-content: center;
        }

        .locate-btn {
            top: 212px;
        }

        .layer-tab {
            min-width: auto;
            flex: 1;
            padding-inline: 10px;
        }
    }


    @media (max-width: 575.98px) {
        .map-search-card input {
            min-width: 0;
            font-size: .88rem;
        }

        .map-shell.sidebar-closed .map-topbar,
        .map-topbar {
            left: 66px;
            width: calc(100% - 76px) !important;
        }

        .category-grid {
            grid-template-columns: 1fr;
        }

        .layer-switches,
        .legend-grid,
        .nearby-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="map-shell" id="mapShell">
    <aside class="map-sidebar">
        <div class="sidebar-scroll">
            <div class="sidebar-intro" style="margin-bottom: 10px;">
                <div class="sidebar-intro-head">
                    <div class="eyebrow"><i class="bi bi-map"></i> Peta Siaga BalamGo</div>
                    <button class="sidebar-collapse" type="button" aria-label="Sembunyikan sidebar">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                </div>
                <h3 style="font-size:1rem; margin-bottom:2px;">Wisata dan fasilitas keselamatan</h3>
                <p style="font-size:.84rem;">Layer wisata, rumah sakit, mitigasi bencana, titik evakuasi, dan buffer keselamatan.</p>
            </div>

            <div class="side-title-row" style="margin-top: 14px;">
                <h2 class="side-title">📊 Ringkasan</h2>
            </div>

            <div class="category-grid" style="gap:10px; margin-bottom: 12px;">
                <div class="mini-chip" style="background:#fff;border-radius:14px; padding:12px 14px; border-color: rgba(226,232,240,.95); box-shadow: 0 12px 28px rgba(22,37,61,.06);">
                    <span style="font-size:1.05rem;">📍</span>
                    <span><strong id="statWisata">0</strong> <span style="color:#334155; font-weight:850;">Wisata</span></span>
                </div>
                <div class="mini-chip" style="background:#fff;border-radius:14px; padding:12px 14px; border-color: rgba(226,232,240,.95); box-shadow: 0 12px 28px rgba(22,37,61,.06);">
                    <span style="font-size:1.05rem;">🏥</span>
                    <span><strong id="statRumahSakit">0</strong> <span style="color:#334155; font-weight:850;">Rumah Sakit</span></span>
                </div>
                <div class="mini-chip" style="background:#fff;border-radius:14px; padding:12px 14px; border-color: rgba(226,232,240,.95); box-shadow: 0 12px 28px rgba(22,37,61,.06);">
                    <span style="font-size:1.05rem;">🚨</span>
                    <span><strong id="statMitigasi">0</strong> <span style="color:#334155; font-weight:850;">Mitigasi</span></span>
                </div>
                <div class="mini-chip" style="background:#fff;border-radius:14px; padding:12px 14px; border-color: rgba(226,232,240,.95); box-shadow: 0 12px 28px rgba(22,37,61,.06);">
                    <i class="bi bi-people-fill" style="color:#38a95c;"></i>
                    <span><strong id="statEvakuasi">0</strong> <span style="color:#334155; font-weight:850;">Evakuasi</span></span>
                </div>
                <div class="mini-chip" style="background:#fff;border-radius:14px; padding:12px 14px; border-color: rgba(226,232,240,.95); box-shadow: 0 12px 28px rgba(22,37,61,.06); grid-column: 1 / -1;">
                    <i class="bi bi-bounding-box-circles" style="color:#8757e8;"></i>
                    <span><strong id="statBuffer">0</strong> <span style="color:#334155; font-weight:850;">Zona Buffer Keselamatan</span></span>
                </div>
            </div>

            <div class="side-title-row" style="margin-top: 10px;">
                <h2 class="side-title">Penjelasan Layer</h2>
            </div>
            <div id="layerInfoList" class="mitigation-grid"></div>
            <div class="side-title-row" style="display:none; margin-top: 6px; margin-bottom: 6px;">
                <h2 class="side-title" style="font-size: .72rem; color: #526070; text-transform: none; letter-spacing: .04em;">Kategori yang muncul sesuai mode</h2>
            </div>

            <div class="side-title-row" style="display:none; margin-top: 4px; margin-bottom: 10px;">
                <h2 class="side-title" style="font-size: .72rem; color: #526070; text-transform: none; letter-spacing: .04em;">Kategori Wisata</h2>
                <button class="side-see-all" type="button" data-check-all="wisata">Pilih semua</button>
            </div>
            <div id="wisataChecks" class="category-grid" style="display:none;"></div>

            <div class="side-title-row" style="display:none; margin-top: 10px; margin-bottom: 10px;">
                <h2 class="side-title" style="font-size: .72rem; color: #526070; text-transform: none; letter-spacing: .04em;">Kategori Mitigasi</h2>
                <button class="side-see-all" type="button" data-check-all="mitigasi">Pilih semua</button>
            </div>
            <div id="mitigasiChecks" class="mitigation-grid" style="display:none;"></div>

            <div class="side-title-row" style="margin-top: 20px; margin-bottom: 10px;">
                <h2 class="side-title">Laporkan Keadaan Darurat</h2>
            </div>
            <form id="emergencyReportForm" class="sidebar-intro" style="box-shadow:none; margin-bottom:0;" onsubmit="event.preventDefault(); alert('Laporan darurat berhasil disiapkan. Untuk bantuan segera, hubungi 112 atau 119.'); this.reset();">
                <div style="display:grid; gap:10px;">
                    <input required type="text" placeholder="Nama pelapor" style="width:100%; border:1px solid var(--map-border); border-radius:12px; padding:10px 12px; font-weight:700;">
                    <input required type="tel" placeholder="Nomor kontak" style="width:100%; border:1px solid var(--map-border); border-radius:12px; padding:10px 12px; font-weight:700;">
                    <select required style="width:100%; border:1px solid var(--map-border); border-radius:12px; padding:10px 12px; font-weight:700; background:#fff;">
                        <option value="">Jenis keadaan</option>
                        <option>Banjir</option>
                        <option>Kebakaran</option>
                        <option>Kecelakaan</option>
                        <option>Medis darurat</option>
                        <option>Lainnya</option>
                    </select>
                    <textarea required rows="3" placeholder="Keterangan lokasi dan kondisi" style="width:100%; border:1px solid var(--map-border); border-radius:12px; padding:10px 12px; font-weight:700; resize:vertical;"></textarea>
                    <button class="danger-btn" type="submit" style="width:100%; justify-content:center;">
                        <i class="bi bi-exclamation-octagon"></i>
                        Kirim Laporan Darurat
                    </button>
                </div>
            </form>

        </div>
    </aside>

    <section class="map-stage">
        <button class="sidebar-open" type="button" id="openSidebarBtn" aria-label="Buka sidebar">
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="map-topbar" style="width:min(920px, calc(100% - 96px));">
            <label class="map-search-card" for="layerSearchInput">
                <i class="bi bi-search"></i>
                <input id="layerSearchInput" type="search" placeholder="Cari nama wisata, rumah sakit, mitigasi, atau titik evakuasi..." autocomplete="off">
            </label>
            <div class="chip-row" id="layerFilterBar" aria-label="Filter layer peta"></div>
            <div id="layerSearchRail" class="search-rail" aria-live="polite">
                <div class="rail-head">
                    <div>
                        <div class="rail-label">Hasil Pencarian</div>
                        <div class="rail-title" id="layerSearchTitle">Cari lokasi</div>
                    </div>
                    <button class="selected-close" type="button" id="clearLayerSearchBtn" aria-label="Tutup hasil pencarian">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="rail-results" id="layerSearchResults"></div>
            </div>
        </div>

        <button class="floating-map-btn locate-btn" type="button" id="locateBtn" aria-label="Lokasi saya">
            <i class="bi bi-geo-alt-fill"></i>
        </button>

        <div id="map"></div>
        <div id="selectedCard" class="selected-card d-none"></div>
        <div id="layerDetailPopup" class="layer-detail-popup d-none"></div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
const map = L.map('map', { zoomControl: false }).setView([-5.4200, 105.2650], 12);
L.control.zoom({ position: 'topright' }).addTo(map);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap &copy; CartoDB'
}).addTo(map);

const markerClusterGroup = L.markerClusterGroup({
    spiderfyOnMaxZoom: true,
    showCoverageOnHover: false,
    disableClusteringAtZoom: 16,
    maxClusterRadius: 50,
    iconCreateFunction: function(cluster) {
        return L.divIcon({
            html: `<div class="cluster-marker"><span>${cluster.getChildCount()}</span></div>`,
            className: '',
            iconSize: [42, 42],
            iconAnchor: [21, 21],
        });
    },
});
map.addLayer(markerClusterGroup);

/*
Legacy API-based wisata/mitigasi renderer is kept commented out because the map
now reads the five public GeoJSON layers directly.

const WISATA_COLORS = {
    "Wisata Alam": "#38a95c",
    "Wisata Bahari": "#1565c8",
    "Wisata Edukasi": "#8757e8",
    "Wisata Religi": "#f28a2e",
    "Wisata Keluarga": "#df3c7b",
    "Wisata Budaya/Taman": "#1aa7a1",
    "Wisata Budaya/Ikon Kota": "#f2ad22",
    "Wisata Kuliner": "#f2ad22",
};
const MITIGASI_COLORS = {
    "Rumah Sakit": "#e64e61",
    "Lembaga Pemerintah": "#f27835",
    "Dinas Pemerintah": "#ef4e45",
    "Fasilitas Kesehatan": "#e64e61",
    "Ruang Terbuka": "#38a95c",
    "Infrastruktur": "#64748b",
    "Lembaga Sosial": "#8757e8",
    "Lembaga Militer": "#334155",
};
const WISATA_ICONS = {
    "Wisata Alam": "bi-image-alt",
    "Wisata Bahari": "bi-water",
    "Wisata Edukasi": "bi-mortarboard-fill",
    "Wisata Religi": "bi-bank2",
    "Wisata Keluarga": "bi-people-fill",
    "Wisata Budaya/Taman": "bi-buildings-fill",
    "Wisata Budaya/Ikon Kota": "bi-flower2",
    "Wisata Kuliner": "bi-fork-knife",
};
const MITIGASI_ICONS = {
    "Rumah Sakit": "bi-plus-square-fill",
    "Lembaga Pemerintah": "bi-cone-striped",
    "Dinas Pemerintah": "bi-fire",
    "Fasilitas Kesehatan": "bi-plus-square-fill",
    "Ruang Terbuka": "bi-person-walking",
    "Infrastruktur": "bi-cone-striped",
    "Lembaga Sosial": "bi-people-fill",
    "Lembaga Militer": "bi-shield-fill",
};
const WISATA_LABELS = {
    "Wisata Alam": "Alam",
    "Wisata Bahari": "Bahari",
    "Wisata Edukasi": "Edukasi",
    "Wisata Religi": "Religi",
    "Wisata Keluarga": "Keluarga",
    "Wisata Budaya/Taman": "Budaya/Taman",
    "Wisata Budaya/Ikon Kota": "Ikon Kota",
    "Wisata Kuliner": "Kuliner",
};
const MITIGASI_LABELS = {
    "Rumah Sakit": "Rumah Sakit",
    "Lembaga Pemerintah": "BPBD",
    "Dinas Pemerintah": "Damkar",
    "Fasilitas Kesehatan": "Rumah Sakit",
    "Ruang Terbuka": "Titik Evakuasi",
    "Infrastruktur": "Infrastruktur",
    "Lembaga Sosial": "Sosial",
    "Lembaga Militer": "Militer",
};
const fallbackImages = [
    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=700&q=80',
];

let wisataRaw = [];
let mitigasiRaw = [];
let markers = [];
let activeMode = 'semua';
let activeWisata = new Set(Object.keys(WISATA_COLORS));
let activeMitigasi = new Set(Object.keys(MITIGASI_COLORS));

function markerIcon(color, icon, type) {
    return L.divIcon({
        className: '',
        html: `<div class="map-marker ${type}" style="background:${color};color:${color}"><i class="bi ${icon}" style="color:#fff"></i></div>`,
        iconSize: [36, 44],
        iconAnchor: [18, 42],
        popupAnchor: [0, -38],
    });
}

function itemName(item, type) { return type === 'wisata' ? item.nama_wisata : item.nama_lokasi; }
function itemUrl(item, type) { return type === 'wisata' ? `/wisata/${item.id}` : `/mitigasi/${item.id}`; }
function itemColor(item, type) { return type === 'wisata' ? (WISATA_COLORS[item.kategori] || '#1565c8') : (MITIGASI_COLORS[item.kategori] || '#38a95c'); }
function itemIcon(item, type) { return type === 'wisata' ? (WISATA_ICONS[item.kategori] || 'bi-camera-fill') : (MITIGASI_ICONS[item.kategori] || 'bi-shield-fill'); }
function itemImage(item, index) { return item.foto ? `/storage/${item.foto}` : fallbackImages[index % fallbackImages.length]; }
function rupiah(value) { return value ? `Rp ${value}` : 'Cek detail'; }
function categoryLabel(category) { return WISATA_LABELS[category] || MITIGASI_LABELS[category] || (category || 'Kategori'); }

function renderAll() {
    markerClusterGroup.clearLayers();
    markers = [];

    const toggleWisata = document.getElementById('toggleWisata');
    const toggleMitigasi = document.getElementById('toggleMitigasi');
    const showWisata = (!toggleWisata || toggleWisata.checked) && (activeMode === 'wisata' || activeMode === 'semua');
    const showMitigasi = (!toggleMitigasi || toggleMitigasi.checked) && (activeMode === 'mitigasi' || activeMode === 'semua');

    if (showWisata) renderMarkers(wisataRaw.filter(w => activeWisata.size === 0 || activeWisata.has(w.kategori)), 'wisata');
    if (showMitigasi) renderMarkers(mitigasiRaw.filter(m => activeMitigasi.size === 0 || activeMitigasi.has(m.kategori)), 'mitigasi');
    renderNearby();
}

function renderMarkers(data, type) {
    data.forEach((item, index) => {
        if (!item.latitude || !item.longitude) return;
        const marker = L.marker([item.latitude, item.longitude], {
            icon: markerIcon(itemColor(item, type), itemIcon(item, type), type)
        });
        marker.on('click', () => showSelected(item, type, index, marker));
        markerClusterGroup.addLayer(marker);
        markers.push({ marker, item, type });
    });
}

function showSelected(item, type, index, marker) {
    map.panTo(marker.getLatLng(), { animate: true });
    const isWisata = type === 'wisata';
    const title = itemName(item, type);
    const distance = (1 + (index % 6) * 0.45).toFixed(1);
    const rating = (4.5 + ((index % 4) * 0.1)).toFixed(1);
    const card = document.getElementById('selectedCard');
    card.classList.remove('d-none');
    card.innerHTML = `
        <div class="selected-image">
            ${isWisata ? `<img src="${itemImage(item, index)}" alt="${title}">` : '<div style="width:100%;height:100%;background:linear-gradient(135deg,#d7e9ff,#b7e3d3)"></div>'}
            <button class="selected-close" type="button" aria-label="Tutup kartu lokasi" onclick="document.getElementById('selectedCard').classList.add('d-none')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="selected-body">
            <span class="type-pill" style="background:${itemColor(item, type)}1f;color:${itemColor(item, type)}"><i class="bi ${itemIcon(item, type)}"></i>${item.kategori || (isWisata ? 'Wisata' : 'Mitigasi')}</span>
            <div class="selected-title">${title}</div>
            <div class="selected-meta"><i class="bi bi-geo-alt"></i> ${item.alamat || item.kecamatan || 'Bandar Lampung'}</div>
            <div class="selected-facts">
                <div class="selected-fact">Rating<strong>${rating} / 5</strong></div>
                <div class="selected-fact">Jarak<strong>${distance} km</strong></div>
                <div class="selected-fact">${isWisata ? 'Harga' : 'Status'}<strong>${isWisata ? rupiah(item.harga_tiket) : (item.status_aktif || 'Aktif')}</strong></div>
                <div class="selected-fact">${isWisata ? 'Jam Buka' : 'Kontak'}<strong>${isWisata ? (item.jam_operasional || 'Lihat detail') : (item.kontak || 'Tersedia')}</strong></div>
            </div>
            <div class="selected-actions">
                <a class="route-btn" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&destination=${item.latitude},${item.longitude}">Rute <i class="bi bi-signpost-2"></i></a>
                <a class="detail-btn" href="${itemUrl(item, type)}">Detail <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    `;
}

function buildWisataChecks() {
    const el = document.getElementById('wisataChecks');
    el.innerHTML = Object.keys(WISATA_COLORS).map(category => `
        <label class="category-tile" title="${category}">
            <input type="checkbox" value="${category}" checked>
            <span class="category-color" style="background:${WISATA_COLORS[category]}"></span>
            <span>${categoryLabel(category)}</span>
        </label>
    `).join('');
    el.querySelectorAll('input').forEach(input => input.addEventListener('change', () => {
        input.checked ? activeWisata.add(input.value) : activeWisata.delete(input.value);
        renderAll();
    }));
}

function buildMitigasiChecks() {
    const el = document.getElementById('mitigasiChecks');
    el.innerHTML = Object.keys(MITIGASI_COLORS).map(category => `
        <label class="mitigation-pill" title="${category}">
            <input type="checkbox" value="${category}" checked>
            <span class="layer-dot" style="background:${MITIGASI_COLORS[category]}"></span>
            <span>${categoryLabel(category)}</span>
        </label>
    `).join('');
    el.querySelectorAll('input').forEach(input => input.addEventListener('change', () => {
        input.checked ? activeMitigasi.add(input.value) : activeMitigasi.delete(input.value);
        renderAll();
    }));
}

function renderNearby() {
    // Pada redesign: rail default tidak menampilkan informasi dummy.
    // Rail hanya muncul saat user mengetik di search.
    const query = (document.getElementById('searchInput')?.value || document.getElementById('mapSearchInput')?.value || '').trim();
    if (query) return;
    const rail = document.getElementById('searchRail');
    const results = document.getElementById('searchResults');
    rail?.classList.remove('is-open');
    if (results) results.innerHTML = '';
}


function searchItems(query) {
    const q = query.trim().toLowerCase();
    const el = document.getElementById('searchResults');
    const rail = document.getElementById('searchRail');
    if (!q) { el.innerHTML = ''; rail.classList.remove('is-open'); return; }
    const results = [
        ...wisataRaw.map((item, index) => ({ item, type: 'wisata', index })),
        ...mitigasiRaw.map((item, index) => ({ item, type: 'mitigasi', index })),
    ].filter(row => itemName(row.item, row.type).toLowerCase().includes(q) || (row.item.kategori || '').toLowerCase().includes(q)).slice(0, 7);
    el.innerHTML = results.length ? results.map(row => `
        <a class="rail-result" href="${itemUrl(row.item, row.type)}">
            <span class="rail-icon" style="background:${itemColor(row.item, row.type)}"><i class="bi ${itemIcon(row.item, row.type)}"></i></span>
            <span class="rail-copy"><strong>${itemName(row.item, row.type)}</strong><span>${row.item.kategori || 'Lokasi'}</span></span>
        </a>
    `).join('') : '<div class="rail-result" style="border-style:dashed;background:#fffaf5"><span class="rail-copy"><strong>Tidak ada hasil</strong><span>Coba kata kunci lain seperti wisata, rumah sakit, atau pantai.</span></span></div>';
    rail.classList.add('is-open');
}

const mapSearchInput = document.getElementById('mapSearchInput');
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', e => {
        mapSearchInput.value = e.target.value;
        searchItems(e.target.value);
    });
}
if (mapSearchInput) {
    mapSearchInput.addEventListener('input', e => {
        searchItems(e.target.value);
        if (searchInput) searchInput.value = e.target.value;
    });
}

document.getElementById('clearSearchBtn').addEventListener('click', () => {
    mapSearchInput.value = '';
    if (searchInput) searchInput.value = '';
    searchItems('');
});
const toggleWisata = document.getElementById('toggleWisata');
const toggleMitigasi = document.getElementById('toggleMitigasi');
if (toggleWisata) toggleWisata.addEventListener('change', renderAll);
if (toggleMitigasi) toggleMitigasi.addEventListener('change', renderAll);
document.querySelectorAll('.layer-tab').forEach(button => button.addEventListener('click', () => {
    document.querySelectorAll('.layer-tab').forEach(tab => tab.classList.remove('active'));
    button.classList.add('active');
    activeMode = button.dataset.mode;
    renderAll();
}));

    document.querySelectorAll('.mini-chip[data-chip]').forEach(button => button.addEventListener('click', () => {
    document.querySelectorAll('.mini-chip[data-chip]').forEach(chip => chip.classList.remove('active'));
    button.classList.add('active');

    const chip = button.dataset.chip;
    if (chip === 'semua') {
        activeMode = 'semua';
        activeWisata = new Set(Object.keys(WISATA_COLORS));
        activeMitigasi = new Set(Object.keys(MITIGASI_COLORS));
    } else if (chip === 'wisata') {
        activeMode = 'wisata';
        activeWisata = new Set(Object.keys(WISATA_COLORS));
        activeMitigasi = new Set();
    } else if (chip === 'mitigasi') {
        activeMode = 'mitigasi';
        activeWisata = new Set();
        activeMitigasi = new Set(Object.keys(MITIGASI_COLORS));
    }

    document.querySelectorAll('#wisataChecks input').forEach(input => {
        input.checked = activeWisata.has(input.value);
    });
    document.querySelectorAll('#mitigasiChecks input').forEach(input => {
        input.checked = activeMitigasi.has(input.value);
    });

    renderAll();
}));

document.querySelectorAll('[data-check-all]').forEach(button => button.addEventListener('click', () => {
    const type = button.dataset.checkAll;
    const selector = type === 'wisata' ? '#wisataChecks input' : '#mitigasiChecks input';
    document.querySelectorAll(selector).forEach(input => {
        input.checked = true;
        type === 'wisata' ? activeWisata.add(input.value) : activeMitigasi.add(input.value);
    });
    renderAll();
}));
document.getElementById('locateBtn').addEventListener('click', () => {
    map.locate({ setView: true, maxZoom: 15 });
});
document.querySelector('.sidebar-collapse').addEventListener('click', () => {
    document.getElementById('mapShell').classList.add('sidebar-closed');
    setTimeout(() => {
        map.invalidateSize();
        document.querySelector('.map-sidebar').style.width = '0';
    }, 260);
});
document.getElementById('openSidebarBtn').addEventListener('click', () => {
    const shell = document.getElementById('mapShell');
    shell.classList.remove('sidebar-closed');
    setTimeout(() => {
        map.invalidateSize();
        const sidebar = document.querySelector('.map-sidebar');
        if (sidebar) {
            sidebar.style.width = '';
            sidebar.style.opacity = '1';
            sidebar.style.pointerEvents = 'auto';
        }
    }, 260);
});

Promise.all([
    fetch('/api/wisata').then(r => r.json()),
    fetch('/api/mitigasi').then(r => r.json()),
]).then(([wisata, mitigasi]) => {
    // Ringkas statistik (dinamis)
    const rsCount = mitigasi.filter(m => (m.kategori || '').toLowerCase().includes('rumah sakit') || (m.kategori || '').toLowerCase().includes('fasilitas kesehatan')).length;
    const mitigasiCount = mitigasi.length;
    const wisataCount = wisata.length;

    const elStatWisata = document.getElementById('statWisata');
    const elStatRS = document.getElementById('statRS');
    const elStatMitigasi = document.getElementById('statMitigasi');
    if (elStatWisata) elStatWisata.textContent = wisataCount;
    if (elStatRS) elStatRS.textContent = rsCount;
    if (elStatMitigasi) elStatMitigasi.textContent = mitigasiCount;


    wisataRaw = wisata;
    mitigasiRaw = mitigasi;

    wisataRaw.forEach(w => {
        if (w.kategori && !WISATA_COLORS[w.kategori]) {
            WISATA_COLORS[w.kategori] = '#1565c8';
            WISATA_ICONS[w.kategori] = 'bi-camera-fill';
            WISATA_LABELS[w.kategori] = w.kategori.replace('Wisata ', '');
        }
        activeWisata.add(w.kategori);
    });
    mitigasiRaw.forEach(m => {
        if (m.kategori && !MITIGASI_COLORS[m.kategori]) {
            MITIGASI_COLORS[m.kategori] = '#38a95c';
            MITIGASI_ICONS[m.kategori] = 'bi-shield-fill';
            MITIGASI_LABELS[m.kategori] = m.kategori;
        }
        activeMitigasi.add(m.kategori);
    });

    buildWisataChecks();
    buildMitigasiChecks();
    renderAll();
}).catch(err => console.error('Gagal memuat data peta:', err));
*/

const layersConfig = {
    wisata: {
        label: 'Wisata',
        url: '/storage/geojson/layer_wisata.geojson',
        color: '#1565c8',
        icon: 'bi-camera-fill',
        kind: 'point',
        desc: '54 titik destinasi wisata Bandar Lampung.',
    },
    rumahSakit: {
        label: 'Rumah Sakit',
        url: '/storage/geojson/layer_rumah_sakit.geojson',
        color: '#e64e61',
        icon: 'bi-hospital',
        kind: 'point',
        desc: '42 titik rumah sakit dan fasilitas kesehatan.',
    },
    mitigasi: {
        label: 'Mitigasi Bencana',
        url: '/storage/geojson/layer_mitigasi_bencana.geojson',
        color: '#f27835',
        icon: 'bi-shield-fill-check',
        kind: 'point',
        desc: '4 titik fasilitas koordinasi dan respons bencana.',
    },
    evakuasi: {
        label: 'Titik Evakuasi',
        url: '/storage/geojson/layer_titik_evakuasi.geojson',
        color: '#38a95c',
        icon: 'bi-people-fill',
        kind: 'point',
        desc: '12 titik kumpul, shelter, dan ruang evakuasi.',
    },
    buffer: {
        label: 'Buffer Keselamatan',
        url: '/storage/geojson/layer_buffer_keselamatan.geojson',
        color: '#8757e8',
        icon: 'bi-bounding-box-circles',
        kind: 'polygon',
        desc: 'Bulatan zona buffer keselamatan; buffer yang berdekatan digabung menjadi satu.',
    },
};

const layerState = Object.fromEntries(Object.keys(layersConfig).map(key => [key, true]));
const layerData = {};
const polygonLayers = {};
let visiblePointMarkers = [];

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>'"]/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;',
    }[char]));
}

function featureProps(feature) {
    return feature?.properties || {};
}

function firstValue(source, keys, fallback = '-') {
    for (const key of keys) {
        const value = source[key];
        if (value !== undefined && value !== null && String(value).trim() !== '') return value;
    }
    return fallback;
}

function featureName(feature, layerKey) {
    return firstValue(featureProps(feature), ['Nama Tempat Wisata', 'Nama Fasilitas', 'Nama', 'Kode'], layersConfig[layerKey].label);
}

function featureAddress(feature) {
    return firstValue(featureProps(feature), ['Alamat (sesuai sumber resmi)', 'Alamat (Google Maps)', 'Alamat', 'Kecamatan'], 'Bandar Lampung');
}

function featureSearchText(feature, key) {
    const props = featureProps(feature);
    return [
        layersConfig[key].label,
        featureName(feature, key),
        featureAddress(feature),
        ...Object.values(props),
    ].join(' ').toLowerCase();
}

function featureLatLng(feature) {
    const props = featureProps(feature);
    const propLat = parseFloat(props.Latitude);
    const propLng = parseFloat(props.Longitude);
    if (Number.isFinite(propLat) && Number.isFinite(propLng)) {
        return [propLat, propLng];
    }

    const coords = feature?.geometry?.coordinates;
    if (Array.isArray(coords) && coords.length >= 2 && typeof coords[0] === 'number') {
        return [coords[1], coords[0]];
    }
    return null;
}

function geoMarkerIcon(color, icon) {
    return L.divIcon({
        className: '',
        html: `<div class="map-marker" style="background:${color};color:${color}"><i class="bi ${icon}" style="color:#fff"></i></div>`,
        iconSize: [36, 44],
        iconAnchor: [18, 42],
        popupAnchor: [0, -38],
    });
}

function buildLayerControls() {
    const filterBar = document.getElementById('layerFilterBar');
    const infoList = document.getElementById('layerInfoList');

    filterBar.innerHTML = Object.entries(layersConfig).map(([key, cfg]) => `
        <button class="mini-chip active" type="button" data-layer-toggle="${key}" style="border-color:${cfg.color}55">
            <span class="layer-dot" style="background:${cfg.color}"></span>${cfg.label}
        </button>
    `).join('');

    infoList.innerHTML = Object.entries(layersConfig).map(([key, cfg]) => `
        <button class="layer-info-button" type="button" data-layer-info="${key}">
            <span class="layer-dot" style="background:${cfg.color}"></span>
            <span>${cfg.label}</span>
        </button>
    `).join('');

    document.querySelectorAll('[data-layer-toggle]').forEach(control => {
        const eventName = control.tagName === 'INPUT' ? 'change' : 'click';
        control.addEventListener(eventName, () => {
            const key = control.dataset.layerToggle;
            setLayerState(key, control.tagName === 'INPUT' ? control.checked : !layerState[key]);
        });
    });

    document.querySelectorAll('[data-layer-info]').forEach(button => {
        button.addEventListener('click', () => showLayerDetail(button.dataset.layerInfo));
    });
}

function setLayerState(key, active) {
    layerState[key] = active;
    syncLayerControls();
    renderGeojsonLayers();
}

function syncLayerControls() {
    document.querySelectorAll('[data-layer-toggle]').forEach(control => {
        const active = layerState[control.dataset.layerToggle];
        if (control.tagName === 'INPUT') control.checked = active;
        control.classList.toggle('active', active);
    });
}

function renderGeojsonLayers() {
    markerClusterGroup.clearLayers();
    Object.values(polygonLayers).forEach(layer => map.removeLayer(layer));
    visiblePointMarkers = [];

    Object.entries(layersConfig).forEach(([key, cfg]) => {
        if (!layerState[key] || !layerData[key]) return;
        if (cfg.kind === 'polygon') {
            renderPolygonLayer(key);
        } else {
            renderPointLayer(key);
        }
    });
}

function renderPointLayer(key) {
    const cfg = layersConfig[key];
    (layerData[key].features || []).forEach((feature, index) => {
        const latLng = featureLatLng(feature);
        if (!latLng) return;
        const marker = L.marker(latLng, { icon: geoMarkerIcon(cfg.color, cfg.icon) });
        marker.on('click', () => showPointCard(feature, key, marker));
        markerClusterGroup.addLayer(marker);
        visiblePointMarkers.push({ marker, feature, key });
    });
}

function distanceMeters(a, b) {
    const earthRadius = 6371000;
    const toRad = value => value * Math.PI / 180;
    const dLat = toRad(b[0] - a[0]);
    const dLng = toRad(b[1] - a[1]);
    const lat1 = toRad(a[0]);
    const lat2 = toRad(b[0]);
    const h = Math.sin(dLat / 2) ** 2 + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dLng / 2) ** 2;
    return earthRadius * 2 * Math.atan2(Math.sqrt(h), Math.sqrt(1 - h));
}

function polygonCenter(feature) {
    const ring = feature?.geometry?.coordinates?.[0] || [];
    const points = ring.filter(point => Array.isArray(point) && point.length >= 2);
    if (!points.length) return featureLatLng(feature);
    const sum = points.reduce((acc, point) => {
        acc.lat += point[1];
        acc.lng += point[0];
        return acc;
    }, { lat: 0, lng: 0 });
    return [sum.lat / points.length, sum.lng / points.length];
}

function clusterBufferFeatures(features) {
    const clusterDistance = 1000;
    const baseBufferRadius = 500;
    const clusters = [];

    (features || []).forEach(feature => {
        const center = polygonCenter(feature);
        if (!center) return;

        let cluster = clusters.find(item => item.centers.some(existing => distanceMeters(existing, center) <= clusterDistance));
        if (!cluster) {
            cluster = { features: [], centers: [] };
            clusters.push(cluster);
        }
        cluster.features.push(feature);
        cluster.centers.push(center);
    });

    return clusters.map(cluster => {
        const center = [
            cluster.centers.reduce((sum, point) => sum + point[0], 0) / cluster.centers.length,
            cluster.centers.reduce((sum, point) => sum + point[1], 0) / cluster.centers.length,
        ];
        const radius = Math.max(
            baseBufferRadius,
            ...cluster.centers.map(point => distanceMeters(center, point) + baseBufferRadius)
        );
        return { ...cluster, center, radius };
    });
}

function renderPolygonLayer(key) {
    const cfg = layersConfig[key];
    if (key === 'buffer') {
        const group = L.layerGroup();
        clusterBufferFeatures(layerData[key].features).forEach(cluster => {
            const circle = L.circle(cluster.center, {
                radius: cluster.radius,
                color: cfg.color,
                weight: 2,
                opacity: 0.85,
                fillColor: cfg.color,
                fillOpacity: 0.14,
            });
            circle.on('click', () => showBufferClusterCard(cluster, key, circle));
            group.addLayer(circle);
        });
        polygonLayers[key] = group.addTo(map);
        return;
    }

    polygonLayers[key] = L.geoJSON(layerData[key], {
        style: {
            color: cfg.color,
            weight: 2,
            opacity: 0.85,
            fillColor: cfg.color,
            fillOpacity: 0.14,
        },
        onEachFeature: (feature, layer) => {
            layer.on('click', () => showPolygonCard(feature, key, layer));
        },
    }).addTo(map);
}

function showBufferClusterCard(cluster, key, circle) {
    const cfg = layersConfig[key];
    const names = cluster.features.map(feature => featureName(feature, key));
    const title = names.length === 1 ? names[0] : `${names.length} buffer keselamatan tergabung`;
    const bounds = circle.getBounds();
    if (bounds.isValid()) map.fitBounds(bounds, { padding: [28, 28] });

    document.getElementById('selectedCard').classList.remove('d-none');
    document.getElementById('selectedCard').innerHTML = `
        <div class="selected-body">
            <button class="selected-close" type="button" aria-label="Tutup kartu lokasi" onclick="document.getElementById('selectedCard').classList.add('d-none')"><i class="bi bi-x-lg"></i></button>
            <span class="type-pill" style="background:${cfg.color}1f;color:${cfg.color}"><i class="bi ${cfg.icon}"></i>${cfg.label}</span>
            <div class="selected-title">${escapeHtml(title)}</div>
            <div class="selected-meta"><i class="bi bi-bounding-box-circles"></i> Radius sekitar ${Math.round(cluster.radius)} meter</div>
            <p style="margin:10px 0 0;color:#5f6b7c;font-size:.86rem;line-height:1.5;">${escapeHtml(names.join(', '))}</p>
        </div>
    `;
}

function showPointCard(feature, key, marker) {
    const cfg = layersConfig[key];
    const props = featureProps(feature);
    const latLng = marker.getLatLng();
    map.panTo(latLng, { animate: true });

    document.getElementById('selectedCard').classList.remove('d-none');
    document.getElementById('selectedCard').innerHTML = `
        <div class="selected-image">
            <div style="width:100%;height:100%;background:linear-gradient(135deg, ${cfg.color}33, #fff);"></div>
            <button class="selected-close" type="button" aria-label="Tutup kartu lokasi" onclick="document.getElementById('selectedCard').classList.add('d-none')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="selected-body">
            <span class="type-pill" style="background:${cfg.color}1f;color:${cfg.color}"><i class="bi ${cfg.icon}"></i>${cfg.label}</span>
            <div class="selected-title">${escapeHtml(featureName(feature, key))}</div>
            <div class="selected-meta"><i class="bi bi-geo-alt"></i> ${escapeHtml(featureAddress(feature))}</div>
            <div class="selected-facts">
                <div class="selected-fact">Jenis<strong>${escapeHtml(firstValue(props, ['Jenis', 'Kategori', 'Sub-Kategori', 'Sub Jenis']))}</strong></div>
                <div class="selected-fact">Jam<strong>${escapeHtml(firstValue(props, ['Jam Operasional']))}</strong></div>
                <div class="selected-fact">Kontak<strong>${escapeHtml(firstValue(props, ['Kontak Darurat', 'Kontak']))}</strong></div>
                <div class="selected-fact">Kapasitas<strong>${escapeHtml(firstValue(props, ['Kapasitas', 'Harga Tiket (Rp)']))}</strong></div>
            </div>
            <div class="selected-actions">
                <a class="route-btn" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&destination=${latLng.lat},${latLng.lng}">Rute <i class="bi bi-signpost-2"></i></a>
            </div>
        </div>
    `;
}

function showPolygonCard(feature, key, layer) {
    const cfg = layersConfig[key];
    const bounds = layer.getBounds();
    if (bounds.isValid()) map.fitBounds(bounds, { padding: [28, 28] });

    document.getElementById('selectedCard').classList.remove('d-none');
    document.getElementById('selectedCard').innerHTML = `
        <div class="selected-body">
            <button class="selected-close" type="button" aria-label="Tutup kartu lokasi" onclick="document.getElementById('selectedCard').classList.add('d-none')"><i class="bi bi-x-lg"></i></button>
            <span class="type-pill" style="background:${cfg.color}1f;color:${cfg.color}"><i class="bi ${cfg.icon}"></i>${cfg.label}</span>
            <div class="selected-title">${escapeHtml(featureName(feature, key))}</div>
            <div class="selected-meta"><i class="bi bi-bounding-box-circles"></i> ${escapeHtml(firstValue(featureProps(feature), ['Sub Jenis', 'Jenis'], 'Zona keselamatan'))}</div>
            <p style="margin:10px 0 0;color:#5f6b7c;font-size:.86rem;line-height:1.5;">Area ini menunjukkan buffer keselamatan di sekitar fasilitas terkait.</p>
        </div>
    `;
}

function layerFeatureRows(key) {
    const features = layerData[key]?.features || [];
    if (!features.length) {
        return '<div class="layer-detail-item"><span class="layer-dot" style="background:#94a3b8"></span><div><strong>Data belum dimuat</strong><span>Coba buka lagi setelah peta selesai tampil.</span></div></div>';
    }

    return features.map((feature, index) => {
        const name = featureName(feature, key);
        const meta = key === 'buffer'
            ? firstValue(featureProps(feature), ['Nama Fasilitas', 'Sub Jenis', 'Jenis'], 'Zona buffer keselamatan')
            : featureAddress(feature);
        return `
            <div class="layer-detail-item">
                <span class="layer-dot" style="background:${layersConfig[key].color};margin-top:4px;"></span>
                <div>
                    <strong>${index + 1}. ${escapeHtml(name)}</strong>
                    <span>${escapeHtml(meta)}</span>
                </div>
            </div>
        `;
    }).join('');
}

function showLayerDetail(key) {
    const cfg = layersConfig[key];
    if (!cfg) return;

    const count = key === 'buffer'
        ? clusterBufferFeatures(layerData.buffer?.features || []).length
        : (layerData[key]?.features || []).length;
    const popup = document.getElementById('layerDetailPopup');

    popup.classList.remove('d-none');
    popup.innerHTML = `
        <div class="layer-detail-head">
            <div>
                <span class="type-pill" style="background:${cfg.color}1f;color:${cfg.color}"><i class="bi ${cfg.icon}"></i>${count} data</span>
                <div class="layer-detail-title">${escapeHtml(cfg.label)}</div>
                <p class="layer-detail-desc">${escapeHtml(cfg.desc)}</p>
            </div>
            <button class="selected-close" type="button" aria-label="Tutup detail layer" onclick="document.getElementById('layerDetailPopup').classList.add('d-none')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="layer-detail-list">
            ${layerFeatureRows(key)}
        </div>
    `;
}

function searchGeojsonFeatures(query) {
    const q = query.trim().toLowerCase();
    if (!q) return [];

    return Object.entries(layersConfig).flatMap(([key, cfg]) => {
        const features = layerData[key]?.features || [];
        return features
            .map((feature, index) => ({ key, cfg, feature, index }))
            .filter(row => featureSearchText(row.feature, key).includes(q));
    }).slice(0, 9);
}

function renderLayerSearch(query) {
    const rail = document.getElementById('layerSearchRail');
    const title = document.getElementById('layerSearchTitle');
    const resultsEl = document.getElementById('layerSearchResults');
    const q = query.trim();

    if (!q) {
        rail.classList.remove('is-open');
        resultsEl.innerHTML = '';
        return;
    }

    const hasData = Object.keys(layerData).length > 0;
    const results = searchGeojsonFeatures(q);
    title.textContent = hasData ? `${results.length} hasil untuk "${q}"` : 'Data peta sedang dimuat';

    resultsEl.innerHTML = results.length ? results.map(row => `
        <button class="rail-result" type="button" data-search-layer="${row.key}" data-search-index="${row.index}">
            <span class="rail-icon" style="background:${row.cfg.color}"><i class="bi ${row.cfg.icon}"></i></span>
            <span class="rail-copy">
                <strong>${escapeHtml(featureName(row.feature, row.key))}</strong>
                <span>${escapeHtml(row.cfg.label)} - ${escapeHtml(featureAddress(row.feature))}</span>
            </span>
        </button>
    `).join('') : `
        <div class="rail-result" style="border-style:dashed;background:#fffaf5;cursor:default;">
            <span class="rail-copy"><strong>Tidak ada hasil</strong><span>Coba nama lokasi, kecamatan, atau jenis fasilitas lain.</span></span>
        </div>
    `;

    rail.classList.add('is-open');
}

function featureBounds(feature) {
    const coords = feature?.geometry?.coordinates;
    const bounds = L.latLngBounds([]);

    function collect(points) {
        if (!Array.isArray(points)) return;
        if (points.length >= 2 && typeof points[0] === 'number' && typeof points[1] === 'number') {
            bounds.extend([points[1], points[0]]);
            return;
        }
        points.forEach(collect);
    }

    collect(coords);
    return bounds;
}

function showSearchFeatureCard(feature, key) {
    const cfg = layersConfig[key];
    const center = featureLatLng(feature) || polygonCenter(feature);
    if (center) map.setView(center, key === 'buffer' ? 13 : 15, { animate: true });

    document.getElementById('selectedCard').classList.remove('d-none');
    document.getElementById('selectedCard').innerHTML = `
        <div class="selected-body">
            <button class="selected-close" type="button" aria-label="Tutup kartu lokasi" onclick="document.getElementById('selectedCard').classList.add('d-none')"><i class="bi bi-x-lg"></i></button>
            <span class="type-pill" style="background:${cfg.color}1f;color:${cfg.color}"><i class="bi ${cfg.icon}"></i>${cfg.label}</span>
            <div class="selected-title">${escapeHtml(featureName(feature, key))}</div>
            <div class="selected-meta"><i class="bi bi-geo-alt"></i> ${escapeHtml(featureAddress(feature))}</div>
            <p style="margin:10px 0 0;color:#5f6b7c;font-size:.86rem;line-height:1.5;">${escapeHtml(firstValue(featureProps(feature), ['Jenis', 'Kategori', 'Sub-Kategori', 'Sub Jenis'], cfg.desc))}</p>
        </div>
    `;
}

function openSearchResult(key, index) {
    const feature = layerData[key]?.features?.[index];
    if (!feature) return;

    if (!layerState[key]) {
        layerState[key] = true;
        syncLayerControls();
        renderGeojsonLayers();
    }

    document.getElementById('layerSearchRail').classList.remove('is-open');
    document.getElementById('layerDetailPopup').classList.add('d-none');

    if (layersConfig[key].kind === 'point') {
        const latLng = featureLatLng(feature);
        if (!latLng) return showSearchFeatureCard(feature, key);
        const found = visiblePointMarkers.find(row => row.key === key && row.feature === feature);
        showPointCard(feature, key, found?.marker || { getLatLng: () => L.latLng(latLng) });
        return;
    }

    const bounds = featureBounds(feature);
    if (bounds.isValid()) map.fitBounds(bounds, { padding: [42, 42] });
    showSearchFeatureCard(feature, key);
}

function updateGeojsonStats() {
    const count = key => layerData[key]?.features?.length || 0;
    document.getElementById('statWisata').textContent = count('wisata');
    document.getElementById('statRumahSakit').textContent = count('rumahSakit');
    document.getElementById('statMitigasi').textContent = count('mitigasi');
    document.getElementById('statEvakuasi').textContent = count('evakuasi');
    document.getElementById('statBuffer').textContent = clusterBufferFeatures(layerData.buffer?.features || []).length;
}

function fitVisibleGeojson() {
    const bounds = L.latLngBounds([]);
    visiblePointMarkers.forEach(marker => bounds.extend(marker.getLatLng()));
    Object.values(polygonLayers).forEach(layer => {
        const layerBounds = layer.getBounds();
        if (layerBounds.isValid()) bounds.extend(layerBounds);
    });
    if (bounds.isValid()) map.fitBounds(bounds, { padding: [32, 32] });
}

document.getElementById('locateBtn').addEventListener('click', () => {
    map.locate({ setView: true, maxZoom: 15 });
});

document.querySelector('.sidebar-collapse').addEventListener('click', () => {
    document.getElementById('mapShell').classList.add('sidebar-closed');
    setTimeout(() => map.invalidateSize(), 260);
});

document.getElementById('openSidebarBtn').addEventListener('click', () => {
    document.getElementById('mapShell').classList.remove('sidebar-closed');
    setTimeout(() => map.invalidateSize(), 260);
});

const layerSearchInput = document.getElementById('layerSearchInput');
const layerSearchResults = document.getElementById('layerSearchResults');
const clearLayerSearchBtn = document.getElementById('clearLayerSearchBtn');

layerSearchInput.addEventListener('input', event => {
    renderLayerSearch(event.target.value);
});

layerSearchInput.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
        layerSearchInput.value = '';
        renderLayerSearch('');
    }
});

layerSearchResults.addEventListener('click', event => {
    const button = event.target.closest('[data-search-layer]');
    if (!button) return;
    openSearchResult(button.dataset.searchLayer, Number(button.dataset.searchIndex));
});

clearLayerSearchBtn.addEventListener('click', () => {
    layerSearchInput.value = '';
    renderLayerSearch('');
    layerSearchInput.focus();
});

buildLayerControls();

Promise.all(Object.entries(layersConfig).map(([key, cfg]) =>
    fetch(cfg.url)
        .then(response => {
            if (!response.ok) throw new Error(`Gagal memuat ${cfg.url}`);
            return response.json();
        })
        .then(data => [key, data])
)).then(entries => {
    entries.forEach(([key, data]) => layerData[key] = data);
    updateGeojsonStats();
    renderGeojsonLayers();
    fitVisibleGeojson();
    renderLayerSearch(layerSearchInput.value);
}).catch(err => console.error('Gagal memuat data GeoJSON:', err));
</script>
@endpush
