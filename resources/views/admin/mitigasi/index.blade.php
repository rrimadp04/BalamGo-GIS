@extends('layouts.admin')
@section('title', 'Data Mitigasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-shield-check text-danger"></i> Data Mitigasi</h4>
    <a href="{{ route('admin.mitigasi.create') }}" class="btn btn-danger">
        <i class="bi bi-plus-lg"></i> Tambah Mitigasi
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <input type="text" id="tableSearch" class="form-control w-25"
                   placeholder="&#128269; Cari mitigasi...">
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="mitigasiTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Lokasi</th>
                        <th>Kategori</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($mitigasi as $i => $m)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $m->nama_lokasi }}</td>
                        <td><span class="badge bg-danger">{{ $m->kategori }}</span></td>
                        <td><small>{{ $m->alamat }}</small></td>
                        <td><small>{{ $m->latitude }}, {{ $m->longitude }}</small></td>
                        <td>
                            <a href="{{ route('admin.mitigasi.edit', $m) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.mitigasi.destroy', $m) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus data mitigasi ini?')">
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
    document.querySelectorAll('#mitigasiTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
