<?php

namespace App\Http\Controllers;

use App\Models\Mitigasi;
use Illuminate\Http\Request;

class MitigasiController extends Controller
{
    public function index()
    {
        $mitigasi = Mitigasi::all();
        return view('admin.mitigasi.index', compact('mitigasi'));
    }

    public function create()
    {
        return view('admin.mitigasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|max:100',
            'kategori'    => 'required|max:50',
            'alamat'      => 'nullable|max:200',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        Mitigasi::create($request->all());

        return redirect()->route('admin.mitigasi.index')
            ->with('success', 'Data mitigasi berhasil ditambahkan!');
    }

    public function edit(Mitigasi $mitigasi)
    {
        return view('admin.mitigasi.edit', compact('mitigasi'));
    }

    public function update(Request $request, Mitigasi $mitigasi)
    {
        $request->validate([
            'nama_lokasi' => 'required|max:100',
            'kategori'    => 'required|max:50',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        $mitigasi->update($request->except(['_method', '_token']));

        return redirect()->route('admin.mitigasi.index')
            ->with('success', 'Data mitigasi berhasil diperbarui!');
    }

    public function destroy(Mitigasi $mitigasi)
    {
        $mitigasi->delete();
        return redirect()->route('admin.mitigasi.index')
            ->with('success', 'Data mitigasi berhasil dihapus!');
    }

    public function apiIndex()
    {
        return response()->json(Mitigasi::all());
    }

    public function showPublic(Mitigasi $mitigasi)
    {
        $wisataTerdekat = \App\Models\Wisata::take(4)->get();

        return view('detail-mitigasi', compact('mitigasi', 'wisataTerdekat'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $mitigasi = Mitigasi::where('nama_lokasi', 'like', "%{$q}%")
            ->orWhere('kategori', 'like', "%{$q}%")
            ->orWhere('alamat', 'like', "%{$q}%")
            ->get();
        return response()->json($mitigasi);
    }
}
