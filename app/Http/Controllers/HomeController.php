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
        $featuredNames = [
            'Bukit Sakura',
            'Taman Kupu-Kupu Gita Persada',
            'Pantai Duta Wisata',
            'Puncak Mas',
        ];

        $destinasiUnggulan = Wisata::whereIn('nama_wisata', $featuredNames)
            ->get()
            ->sortBy(fn ($wisata) => array_search($wisata->nama_wisata, $featuredNames, true))
            ->values();

        return view('home', compact(
            'totalWisata', 'totalMitigasi',
            'totalKategori', 'totalEvakuasi',
            'destinasiUnggulan'
        ));
    }
}
