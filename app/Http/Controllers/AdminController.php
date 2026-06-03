<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Mitigasi;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalWisata   = Wisata::count();
        $totalMitigasi = Mitigasi::count();

        $wisataPerKategori   = Wisata::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')->get();
        $mitigasiPerKategori = Mitigasi::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')->get();

        return view('admin.dashboard', compact(
            'totalWisata', 'totalMitigasi',
            'wisataPerKategori', 'mitigasiPerKategori'
        ));
    }
}
