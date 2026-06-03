@extends('layouts.admin')
@section('title', 'Data Wisata')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-signpost-2 text-primary"></i> Data Wisata</h4>
    <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Wisata
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <input type="text" id="tableSearch" class="form-control w-25"
                   placeholder="&#128269; Cari wisata...">
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="wisataTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Wisata</th>
                        <th>Kategori</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($wisata as $index => $w)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $w->nama_wisata }}</td>
                        <td><span class="badge bg-success">{{ $w->kategori }}</span></td>
                        <td><small>{{ $w->alamat }}</small></td>
                        <td><small>{{ $w->latitude }}, {{ $w->longitude }}</small></td>
                        <td>
                            @if($w->foto)
                                <img src="{{ Storage::url($w->foto) }}" height="40" class="rounded">
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.wisata.edit', $w) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.wisata.destroy', $w) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus wisata ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('tableSearch').addEventListener('keyup', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#wisataTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
