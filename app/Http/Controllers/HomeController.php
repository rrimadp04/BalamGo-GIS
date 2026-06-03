<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Mitigasi;

class HomeController extends Controller
{
    public function index()
    {
        $totalWisata      = Wisata::count();
        $totalMitigasi    = Mitigasi::count();
        $totalKategori    = Wisata::distinct('kategori')->count('kategori');
        $totalEvakuasi    = Mitigasi::whereIn('kategori', ['Ruang Terbuka', 'Infrastruktur'])->count();
        $destinasiUnggulan = Wisata::take(4)->get();

        return view('home', compact(
            'totalWisata', 'totalMitigasi',
            'totalKategori', 'totalEvakuasi',
            'destinasiUnggulan'
        ));
    }
}
