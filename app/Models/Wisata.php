<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisata';

    protected $fillable = [
        'nama_wisata', 'kategori', 'sub_kategori',
        'alamat', 'kelurahan', 'kecamatan',
        'deskripsi', 'latitude', 'longitude',
        'jam_operasional', 'harga_tiket', 'fasilitas',
        'kapasitas', 'kontak', 'website', 'foto',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];
}
