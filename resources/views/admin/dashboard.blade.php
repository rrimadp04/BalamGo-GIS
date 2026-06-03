@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-speedometer2 text-primary"></i> Dashboard BalamGo</h4>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 bg-success text-white fs-4">
                    <i class="bi bi-signpost-2"></i>
                </div>
                <div>
                    <div class="fw-bold fs-2 text-success">{{ $totalWisata }}</div>
                    <div class="text-muted">Total Wisata</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 bg-danger text-white fs-4">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <div class="fw-bold fs-2 text-danger">{{ $totalMitigasi }}</div>
                    <div class="text-muted">Total Mitigasi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 bg-primary text-white fs-4">
                    <i class="bi bi-map"></i>
                </div>
                <div>
                    <div class="fw-bold fs-2 text-primary">{{ $totalWisata + $totalMitigasi }}</div>
                    <div class="text-muted">Total Lokasi</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold"><i class="bi bi-bar-chart text-success"></i> Wisata per Kategori</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Kategori</th><th>Jumlah</th></tr></thead>
                    <tbody>
                    @foreach($wisataPerKategori as $item)
                        <tr>
                            <td><span class="badge bg-success">{{ $item->kategori }}</span></td>
                            <td><strong>{{ $item->total }}</strong></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold"><i class="bi bi-bar-chart text-danger"></i> Mitigasi per Kategori</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Kategori</th><th>Jumlah</th></tr></thead>
                    <tbody>
                    @foreach($mitigasiPerKategori as $item)
                        <tr>
                            <td><span class="badge bg-danger">{{ $item->kategori }}</span></td>
                            <td><strong>{{ $item->total }}</strong></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
