@extends('layouts.admin')
@section('title', 'Tambah Mitigasi')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-plus-circle text-danger"></i> Tambah Fasilitas Mitigasi</h4>

<div class="card shadow-sm" style="max-width:700px">
    <div class="card-body">
        <form action="{{ route('admin.mitigasi.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lokasi *</label>
                <input type="text" name="nama_lokasi"
                       class="form-control @error('nama_lokasi') is-invalid @enderror"
                       value="{{ old('nama_lokasi') }}" required>
                @error('nama_lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori *</label>
                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Rumah Sakit', 'BPBD', 'PMI', 'Basarnas', 'Damkar', 'Puskesmas', 'Evakuasi'] as $k)
                        <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Latitude *</label>
                    <input type="number" name="latitude" step="any"
                           class="form-control @error('latitude') is-invalid @enderror"
                           value="{{ old('latitude') }}" placeholder="-5.45" required>
                    @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Longitude *</label>
                    <input type="number" name="longitude" step="any"
                           class="form-control @error('longitude') is-invalid @enderror"
                           value="{{ old('longitude') }}" placeholder="105.26" required>
                    @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.mitigasi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
