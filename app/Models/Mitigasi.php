<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitigasi extends Model
{
    use HasFactory;

    protected $table = 'mitigasi';

    protected $fillable = [
        'nama_lokasi', 'kategori', 'jenis_fasilitas', 'sub_jenis',
        'alamat', 'kelurahan', 'kecamatan',
        'latitude', 'longitude',
        'jam_operasional', 'kapasitas', 'layanan',
        'kontak', 'status_aktif',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];
}
