@extends('layouts.app')

@section('title', 'Peta Interaktif BalamGo')

@push('styles')
<style>
    body { overflow: hidden; background: #f4f6f8; }
    .map-shell { height: calc(100vh - 57px); display: grid; grid-template-columns: 360px minmax(0, 1fr); }
    .map-sidebar { position: relative; z-index: 5; display: flex; flex-direction: column; min-height: 0; background: rgba(255,255,255,.94); border-right: 1px solid #dce3ea; box-shadow: 12px 0 32px rgba(20, 38, 64, .08); }
    .sidebar-scroll { flex: 1; overflow: auto; padding: 22px 20px; }
    .map-stage { position: relative; min-width: 0; min-height: 0; }
    #map { height: 100%; width: 100%; background: #dfe3e7; }
    .layer-tabs { position: absolute; top: 16px; left: 50%; transform: translateX(-50%); z-index: 800; display: flex; gap: 4px; padding: 4px; border-radius: 999px; background: rgba(255,255,255,.82); box-shadow: 0 10px 28px rgba(18, 35, 58, .14); backdrop-filter: blur(12px); }
    .layer-tab { min-width: 116px; border: 0; border-radius: 999px; padding: 9px 20px; color: #4b5565; font-weight: 800; background: transparent; }
    .layer-tab.active { color: #fff; background: #075db6; }
    .search-box { display: flex; align-items: center; gap: 10px; min-height: 46px; padding: 0 14px; border-radius: 10px; background: #eef1f4; color: #667085; }
    .search-box input { width: 100%; border: 0; outline: 0; background: transparent; color: #1d2735; }
    .side-title { margin: 24px 0 12px; color: #475062; font-size: .78rem; font-weight: 850; letter-spacing: .09em; text-transform: uppercase; }
    .filter-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 16px; }
    .check-row { display: flex; align-items: center; gap: 8px; min-width: 0; color: #263140; font-weight: 650; }
    .check-row input { width: 17px; height: 17px; accent-color: #075db6; }
    .legend-list { display: grid; gap: 8px; }
    .legend-item { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 9px 10px; border: 1px solid #e6ebf0; border-radius: 8px; background: #fff; color: #334155; font-size: .9rem; font-weight: 700; }
    .legend-name { display: flex; align-items: center; gap: 9px; min-width: 0; }
    .legend-dot { width: 13px; height: 13px; flex: 0 0 13px; border-radius: 99px; box-shadow: 0 0 0 3px rgba(0,0,0,.04); }
    .legend-count { color: #8b95a5; font-size: .78rem; }
    .sidebar-footer { padding: 16px; border-top: 1px solid #dce3ea; }
    .danger-btn { display: flex; align-items: center; justify-content: center; gap: 9px; min-height: 48px; width: 100%; border: 0; border-radius: 8px; background: linear-gradient(90deg, #ff7a18, #ff4f17); color: #fff; font-weight: 850; box-shadow: 0 12px 26px rgba(255, 105, 24, .25); }
    .map-marker { width: 36px; height: 44px; display: grid; place-items: center; border-radius: 18px 18px 18px 4px; transform: rotate(-45deg); color: #fff; border: 3px solid #fff; box-shadow: 0 9px 22px rgba(16, 32, 54, .28); }
    .map-marker i { transform: rotate(45deg); font-size: 17px; }
    .map-marker.mitigasi { border-color: #fff; }
    .leaflet-popup-content-wrapper, .leaflet-popup-tip { display: none; }
    .selected-card { position: absolute; top: 78px; right: 22px; z-index: 850; width: min(360px, calc(100vw - 420px)); overflow: hidden; border-radius: 12px; background: #fff; box-shadow: 0 22px 58px rgba(16, 31, 52, .2); border: 1px solid rgba(220, 227, 234, .95); }
    .selected-image { height: 150px; background: linear-gradient(135deg, #b5ebe1, #6ab7df); position: relative; overflow: hidden; }
    .selected-image img { width: 100%; height: 100%; object-fit: cover; }
    .selected-close { position: absolute; top: 10px; right: 10px; width: 34px; height: 34px; display: grid; place-items: center; border: 0; border-radius: 99px; background: rgba(255,255,255,.9); color: #1f2937; }
    .selected-body { padding: 16px; }
    .type-pill { display: inline-flex; align-items: center; gap: 6px; min-height: 24px; padding: 3px 9px; border-radius: 999px; background: #e9f5ff; color: #07549b; font-size: .72rem; font-weight: 850; text-transform: uppercase; }
    .selected-title { margin: 10px 0 6px; font-size: 1.2rem; font-weight: 850; color: #162033; }
    .selected-meta { color: #687384; font-size: .92rem; line-height: 1.5; }
    .selected-facts { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin: 14px 0; }
    .selected-fact { padding: 10px; border-radius: 8px; background: #f5f7f9; color: #4a5565; font-size: .78rem; font-weight: 700; }
    .selected-fact strong { display: block; color: #162033; font-size: .95rem; margin-top: 3px; }
    .detail-btn { display: flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px; border-radius: 8px; background: #07549b; color: #fff; text-decoration: none; font-weight: 850; }
    .detail-btn:hover { color: #fff; background: #06457e; }
    .nearby-panel { position: absolute; left: 0; right: 0; bottom: 0; z-index: 700; padding: 16px 24px 18px; border-top: 1px solid #dce3ea; background: rgba(255,255,255,.92); backdrop-filter: blur(10px); box-shadow: 0 -12px 35px rgba(16, 31, 52, .08); }
    .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 12px; }
    .panel-title { display: flex; align-items: center; gap: 10px; color: #172033; font-size: 1rem; font-weight: 850; }
    .panel-title i { color: #075db6; }
    .nearby-grid { display: grid; grid-template-columns: repeat(3, minmax(170px, 1fr)); gap: 16px; max-width: 920px; }
    .nearby-card { overflow: hidden; border-radius: 9px; border: 1px solid #e2e8ef; background: #fff; box-shadow: 0 8px 18px rgba(22, 37, 61, .08); cursor: pointer; }
    .nearby-card img { width: 100%; height: 88px; object-fit: cover; }
    .nearby-body { padding: 10px 12px; }
    .nearby-name { font-weight: 850; color: #172033; line-height: 1.2; }
    .nearby-row { display: flex; justify-content: space-between; gap: 8px; margin-top: 8px; color: #f97316; font-weight: 800; font-size: .86rem; }
    .leaflet-control-zoom { border: 0 !important; box-shadow: 0 8px 22px rgba(16,31,52,.16) !important; }
    .leaflet-control-zoom a { border: 0 !important; color: #111827 !important; }
    @media (max-width: 991.98px) {
        body { overflow: auto; }
        .map-shell { height: auto; min-height: calc(100vh - 57px); grid-template-columns: 1fr; }
        .map-sidebar { max-height: none; }
        .map-stage { height: 760px; }
        .selected-card { left: 16px; right: 16px; top: 72px; width: auto; }
        .nearby-grid { grid-template-columns: 1fr; }
        .nearby-panel { position: relative; }
        .layer-tabs { top: 12px; }
        .layer-tab { min-width: auto; padding-inline: 14px; }
    }
</style>
@endpush

@section('content')
<div class="map-shell">
    <aside class="map-sidebar">
        <div class="sidebar-scroll">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari wisata atau fasilitas..." autocomplete="off">
            </div>
            <div id="searchResults" class="mt-2"></div>

            <div class="side-title">Layer</div>
            <div class="filter-grid">
                <label class="check-row"><input type="checkbox" id="toggleWisata" checked> Wisata</label>
                <label class="check-row"><input type="checkbox" id="toggleMitigasi" checked> Mitigasi</label>
            </div>

            <div class="side-title">Kategori Wisata</div>
            <div id="wisataChecks" class="filter-grid"></div>

            <div class="side-title">Kategori Mitigasi</div>
            <div id="mitigasiChecks" class="filter-grid"></div>

            <div class="side-title">Legenda Wisata</div>
            <div id="legendWisata" class="legend-list"></div>

            <div class="side-title">Legenda Mitigasi</div>
            <div id="legendMitigasi" class="legend-list"></div>
        </div>
        <div class="sidebar-footer">
            <button class="danger-btn" type="button"><i class="bi bi-exclamation-octagon"></i> Laporkan Keadaan Darurat</button>
        </div>
    </aside>

    <section class="map-stage">
        <div class="layer-tabs">
            <button class="layer-tab active" type="button" data-mode="wisata">Wisata</button>
            <button class="layer-tab" type="button" data-mode="mitigasi">Mitigasi</button>
            <button class="layer-tab" type="button" data-mode="semua">Semua</button>
        </div>
        <div id="map"></div>
        <div id="selectedCard" class="selected-card d-none"></div>

        <div class="nearby-panel">
            <div class="panel-head">
                <div class="panel-title"><i class="bi bi-compass"></i> Destinasi Terdekat dari Saya</div>
                <a href="#" class="fw-bold text-decoration-none" id="showAllItems">Lihat Semua</a>
            </div>
            <div id="nearbyGrid" class="nearby-grid"></div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
const map = L.map('map', { zoomControl: false }).setView([-5.4200, 105.2650], 12);
L.control.zoom({ position: 'topright' }).addTo(map);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap &copy; CartoDB'
}).addTo(map);

const WISATA_COLORS = {
    "Wisata Alam": "#16a34a",
    "Wisata Bahari": "#0284c7",
    "Wisata Edukasi": "#f97316",
    "Wisata Religi": "#7c3aed",
    "Wisata Keluarga": "#db2777",
    "Wisata Budaya/Taman": "#0d9488",
    "Wisata Budaya/Ikon Kota": "#d97706",
};
const MITIGASI_COLORS = {
    "Rumah Sakit": "#dc2626",
    "Lembaga Pemerintah": "#2563eb",
    "Dinas Pemerintah": "#b91c1c",
    "Fasilitas Kesehatan": "#059669",
    "Ruang Terbuka": "#ca8a04",
    "Infrastruktur": "#64748b",
    "Lembaga Sosial": "#9333ea",
    "Lembaga Militer": "#334155",
};
const WISATA_ICONS = {
    "Wisata Alam": "bi-tree",
    "Wisata Bahari": "bi-water",
    "Wisata Edukasi": "bi-mortarboard",
    "Wisata Religi": "bi-building",
    "Wisata Keluarga": "bi-balloon",
    "Wisata Budaya/Taman": "bi-flower1",
    "Wisata Budaya/Ikon Kota": "bi-bank",
};
const MITIGASI_ICONS = {
    "Rumah Sakit": "bi-hospital",
    "Lembaga Pemerintah": "bi-bank",
    "Dinas Pemerintah": "bi-truck",
    "Fasilitas Kesehatan": "bi-capsule",
    "Ruang Terbuka": "bi-geo",
    "Infrastruktur": "bi-cone-striped",
    "Lembaga Sosial": "bi-people",
    "Lembaga Militer": "bi-shield",
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
let activeMode = 'wisata';
let activeWisata = new Set(Object.keys(WISATA_COLORS));
let activeMitigasi = new Set(Object.keys(MITIGASI_COLORS));

function markerIcon(color, icon, type) {
    return L.divIcon({
        className: '',
        html: `<div class="map-marker ${type}" style="background:${color}"><i class="bi ${icon}"></i></div>`,
        iconSize: [36, 44],
        iconAnchor: [18, 42],
        popupAnchor: [0, -38],
    });
}

function itemName(item, type) { return type === 'wisata' ? item.nama_wisata : item.nama_lokasi; }
function itemUrl(item, type) { return type === 'wisata' ? `/wisata/${item.id}` : `/mitigasi/${item.id}`; }
function itemColor(item, type) { return type === 'wisata' ? (WISATA_COLORS[item.kategori] || '#16a34a') : (MITIGASI_COLORS[item.kategori] || '#dc2626'); }
function itemIcon(item, type) { return type === 'wisata' ? (WISATA_ICONS[item.kategori] || 'bi-geo-alt') : (MITIGASI_ICONS[item.kategori] || 'bi-shield-exclamation'); }
function itemImage(item, index) { return item.foto ? `/storage/${item.foto}` : fallbackImages[index % fallbackImages.length]; }
function rupiah(value) { return value ? `Rp ${value}` : 'Cek detail'; }

function renderAll() {
    markers.forEach(({ marker }) => map.removeLayer(marker));
    markers = [];
    const showWisata = document.getElementById('toggleWisata').checked && (activeMode === 'wisata' || activeMode === 'semua');
    const showMitigasi = document.getElementById('toggleMitigasi').checked && (activeMode === 'mitigasi' || activeMode === 'semua');

    if (showWisata) renderMarkers(wisataRaw.filter(w => activeWisata.has(w.kategori)), 'wisata');
    if (showMitigasi) renderMarkers(mitigasiRaw.filter(m => activeMitigasi.has(m.kategori)), 'mitigasi');
    renderNearby();
}

function renderMarkers(data, type) {
    data.forEach((item, index) => {
        if (!item.latitude || !item.longitude) return;
        const marker = L.marker([item.latitude, item.longitude], {
            icon: markerIcon(itemColor(item, type), itemIcon(item, type), type)
        }).addTo(map);
        marker.on('click', () => showSelected(item, type, index, marker));
        markers.push({ marker, item, type });
    });
}

function showSelected(item, type, index, marker) {
    map.panTo(marker.getLatLng(), { animate: true });
    const isWisata = type === 'wisata';
    const title = itemName(item, type);
    const card = document.getElementById('selectedCard');
    card.classList.remove('d-none');
    card.innerHTML = `
        <div class="selected-image">
            ${isWisata ? `<img src="${itemImage(item, index)}" alt="${title}">` : ''}
            <button class="selected-close" type="button" onclick="document.getElementById('selectedCard').classList.add('d-none')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="selected-body">
            <span class="type-pill" style="background:${itemColor(item, type)}1f;color:${itemColor(item, type)}"><i class="bi ${itemIcon(item, type)}"></i>${item.kategori || (isWisata ? 'Wisata' : 'Mitigasi')}</span>
            <div class="selected-title">${title}</div>
            <div class="selected-meta"><i class="bi bi-geo-alt"></i> ${item.alamat || item.kecamatan || 'Bandar Lampung'}</div>
            <div class="selected-facts">
                <div class="selected-fact">${isWisata ? 'Harga' : 'Status'}<strong>${isWisata ? rupiah(item.harga_tiket) : (item.status_aktif || 'Aktif')}</strong></div>
                <div class="selected-fact">${isWisata ? 'Jam Buka' : 'Kontak'}<strong>${isWisata ? (item.jam_operasional || 'Lihat detail') : (item.kontak || 'Tersedia')}</strong></div>
            </div>
            <a class="detail-btn" href="${itemUrl(item, type)}">Lihat Detail <i class="bi bi-arrow-right"></i></a>
        </div>
    `;
}

function buildChecks(elId, colors, activeSet, type) {
    const el = document.getElementById(elId);
    el.innerHTML = Object.keys(colors).map(category => `
        <label class="check-row" title="${category}">
            <input type="checkbox" value="${category}" data-type="${type}" checked>
            <span class="text-truncate">${category.replace('Wisata ', '')}</span>
        </label>
    `).join('');
    el.querySelectorAll('input').forEach(input => input.addEventListener('change', () => {
        input.checked ? activeSet.add(input.value) : activeSet.delete(input.value);
        renderAll();
    }));
}

function buildLegend(elId, colors, data) {
    const counts = data.reduce((acc, item) => {
        acc[item.kategori] = (acc[item.kategori] || 0) + 1;
        return acc;
    }, {});
    document.getElementById(elId).innerHTML = Object.keys(colors).map(category => `
        <div class="legend-item">
            <span class="legend-name"><span class="legend-dot" style="background:${colors[category]}"></span><span class="text-truncate">${category}</span></span>
            <span class="legend-count">${counts[category] || 0}</span>
        </div>
    `).join('');
}

function renderNearby() {
    const source = wisataRaw.length ? wisataRaw.slice(0, 3) : [];
    document.getElementById('nearbyGrid').innerHTML = source.map((item, index) => `
        <article class="nearby-card" onclick="location.href='/wisata/${item.id}'">
            <img src="${itemImage(item, index)}" alt="${item.nama_wisata}">
            <div class="nearby-body">
                <div class="nearby-name">${item.nama_wisata}</div>
                <div class="nearby-row"><span><i class="bi bi-star-fill"></i> ${(4.6 + (index * .1)).toFixed(1)}</span><span>${rupiah(item.harga_tiket)}</span></div>
            </div>
        </article>
    `).join('');
}

function searchItems(query) {
    const q = query.trim().toLowerCase();
    const el = document.getElementById('searchResults');
    if (!q) { el.innerHTML = ''; return; }
    const results = [
        ...wisataRaw.map((item, index) => ({ item, type: 'wisata', index })),
        ...mitigasiRaw.map((item, index) => ({ item, type: 'mitigasi', index })),
    ].filter(row => itemName(row.item, row.type).toLowerCase().includes(q) || (row.item.kategori || '').toLowerCase().includes(q)).slice(0, 7);
    el.innerHTML = results.length ? results.map(row => `
        <a class="legend-item text-decoration-none" href="${itemUrl(row.item, row.type)}">
            <span class="legend-name"><span class="legend-dot" style="background:${itemColor(row.item, row.type)}"></span><span class="text-truncate">${itemName(row.item, row.type)}</span></span>
            <span class="legend-count">${row.type}</span>
        </a>
    `).join('') : '<div class="legend-item">Tidak ditemukan</div>';
}

document.getElementById('searchInput').addEventListener('input', e => searchItems(e.target.value));
document.getElementById('toggleWisata').addEventListener('change', renderAll);
document.getElementById('toggleMitigasi').addEventListener('change', renderAll);
document.querySelectorAll('.layer-tab').forEach(button => button.addEventListener('click', () => {
    document.querySelectorAll('.layer-tab').forEach(tab => tab.classList.remove('active'));
    button.classList.add('active');
    activeMode = button.dataset.mode;
    renderAll();
}));

buildChecks('wisataChecks', WISATA_COLORS, activeWisata, 'wisata');
buildChecks('mitigasiChecks', MITIGASI_COLORS, activeMitigasi, 'mitigasi');

Promise.all([
    fetch('/api/wisata').then(r => r.json()),
    fetch('/api/mitigasi').then(r => r.json()),
]).then(([wisata, mitigasi]) => {
    wisataRaw = wisata;
    mitigasiRaw = mitigasi;
    buildLegend('legendWisata', WISATA_COLORS, wisataRaw);
    buildLegend('legendMitigasi', MITIGASI_COLORS, mitigasiRaw);
    renderAll();
});
</script>
@endpush
