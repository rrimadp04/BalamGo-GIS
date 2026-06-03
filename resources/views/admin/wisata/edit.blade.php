@extends('layouts.admin')
@section('title', 'Edit Wisata')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-pencil-square text-warning"></i> Edit Wisata</h4>

<div class="card shadow-sm" style="max-width:700px">
    <div class="card-body">
        <form action="{{ route('admin.wisata.update', $wisata) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Wisata *</label>
                <input type="text" name="nama_wisata"
                       class="form-control @error('nama_wisata') is-invalid @enderror"
                       value="{{ old('nama_wisata', $wisata->nama_wisata) }}" required>
                @error('nama_wisata')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori *</label>
                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Alam', 'Pantai', 'Religi', 'Edukasi', 'Hiburan'] as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $wisata->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <input type="text" name="alamat" class="form-control"
                       value="{{ old('alamat', $wisata->alamat) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Latitude *</label>
                    <input type="number" name="latitude" step="any"
                           class="form-control @error('latitude') is-invalid @enderror"
                           value="{{ old('latitude', $wisata->latitude) }}" required>
                    @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Longitude *</label>
                    <input type="number" name="longitude" step="any"
                           class="form-control @error('longitude') is-invalid @enderror"
                           value="{{ old('longitude', $wisata->longitude) }}" required>
                    @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Foto</label>
                @if($wisata->foto)
                    <div class="mb-2">
                        <img src="{{ Storage::url($wisata->foto) }}" height="80" class="rounded">
                        <small class="text-muted ms-2">Foto saat ini</small>
                    </div>
                @endif
                <input type="file" name="foto"
                       class="form-control @error('foto') is-invalid @enderror"
                       accept="image/jpg,image/jpeg,image/png">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG/PNG, Maks: 2MB</small>
                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
