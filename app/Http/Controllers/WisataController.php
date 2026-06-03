<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WisataController extends Controller
{
    // Tampilkan daftar wisata (untuk admin)
    public function index()
    {
        $wisata = Wisata::all();
        return view('admin.wisata.index', compact('wisata'));
    }

    // Form tambah wisata
    public function create()
    {
        return view('admin.wisata.create');
    }

    // Simpan wisata baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_wisata' => 'required|max:100',
            'kategori' => 'required|max:50',
            'alamat' => 'nullable|max:200',
            'deskripsi' => 'nullable',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('wisata', 'public');
        }

        Wisata::create($data);

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Data wisata berhasil ditambahkan!');
    }

    // Form edit wisata
    public function edit(Wisata $wisata)
    {
        return view('admin.wisata.edit', compact('wisata'));
    }

    // Simpan perubahan wisata
    public function update(Request $request, Wisata $wisata)
    {
        $request->validate([
            'nama_wisata' => 'required|max:100',
            'kategori' => 'required|max:50',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['foto', '_method', '_token']);

        if ($request->hasFile('foto')) {
            if ($wisata->foto) {
                Storage::disk('public')->delete($wisata->foto);
            }

            $data['foto'] = $request->file('foto')
                ->store('wisata', 'public');
        }

        $wisata->update($data);

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Data wisata berhasil diperbarui!');
    }

    // Hapus wisata
    public function destroy(Wisata $wisata)
    {
        if ($wisata->foto) {
            Storage::disk('public')->delete($wisata->foto);
        }

        $wisata->delete();

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Data wisata berhasil dihapus!');
    }

    // API: Return JSON untuk Leaflet
    public function apiIndex()
    {
        return response()->json(
            Wisata::select('id','nama_wisata','kategori','alamat','kecamatan','latitude','longitude','foto','harga_tiket','jam_operasional','kontak')->get()
        );
    }

    public function showPublic(Wisata $wisata)
    {
        $rekomendasi = Wisata::where('id', '!=', $wisata->id)->take(4)->get();
        return view('detail-wisata', compact('wisata', 'rekomendasi'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $wisata = Wisata::where('nama_wisata', 'like', "%{$q}%")
            ->orWhere('kategori', 'like', "%{$q}%")
            ->orWhere('alamat', 'like', "%{$q}%")
            ->get();
        return response()->json($wisata);
    }
}
