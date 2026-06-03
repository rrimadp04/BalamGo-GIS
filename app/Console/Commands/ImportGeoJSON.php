<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wisata;
use App\Models\Mitigasi;

class ImportGeoJSON extends Command
{
    protected $signature   = 'import:geojson';
    protected $description = 'Import GeoJSON wisata dan mitigasi ke database';

    public function handle()
    {
        // === IMPORT WISATA ===
        $this->info('Importing wisata...');
        $path = storage_path('app/public/geojson/BalamGo_DataWisata.geojson');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: {$path}");
        } else {
            $geojson = json_decode(file_get_contents($path), true);
            $count = 0;
            foreach ($geojson['features'] as $feature) {
                $p      = $feature['properties'];
                $coords = $feature['geometry']['coordinates'];

                Wisata::updateOrCreate(
                    ['nama_wisata' => $p['Nama Tempat Wisata']],
                    [
                        'kategori'        => $p['Kategori']            ?? '',
                        'sub_kategori'    => $p['Sub-Kategori']        ?? '',
                        'alamat'          => $p['Alamat Lengkap']      ?? '',
                        'kelurahan'       => $p['Kelurahan']           ?? '',
                        'kecamatan'       => $p['Kecamatan']           ?? '',
                        'deskripsi'       => $p['Keterangan']          ?? '',
                        'jam_operasional' => $p['Jam Operasional']     ?? '',
                        'harga_tiket'     => $p['Harga Tiket (Rp)']   ?? '',
                        'fasilitas'       => $p['Fasilitas Tersedia']  ?? '',
                        'kapasitas'       => $p['Kapasitas Pengunjung'] ?? '',
                        'kontak'          => $p['Kontak/Telp']         ?? '',
                        'website'         => $p['Website/Instagram']   ?? '',
                        'longitude'       => $coords[0],
                        'latitude'        => $coords[1],
                    ]
                );
                $count++;
            }
            $this->info("Imported {$count} wisata records.");
        }

        // === IMPORT MITIGASI ===
        $this->info('Importing mitigasi...');
        $path = storage_path('app/public/geojson/BalamGo_DataMitigasi.geojson');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: {$path}");
        } else {
            $geojson = json_decode(file_get_contents($path), true);
            $count = 0;
            foreach ($geojson['features'] as $feature) {
                $p      = $feature['properties'];
                $coords = $feature['geometry']['coordinates'];

                Mitigasi::updateOrCreate(
                    ['nama_lokasi' => $p['Nama Fasilitas']],
                    [
                        'kategori'        => $p['Jenis Fasilitas']          ?? '',
                        'jenis_fasilitas' => $p['Jenis Fasilitas']          ?? '',
                        'sub_jenis'       => $p['Sub-Jenis']                ?? '',
                        'alamat'          => $p['Alamat Lengkap']           ?? '',
                        'kelurahan'       => $p['Kelurahan']                ?? '',
                        'kecamatan'       => $p['Kecamatan']                ?? '',
                        'jam_operasional' => $p['Jam Operasional']          ?? '',
                        'kapasitas'       => $p['Kapasitas (Orang/Bed)']    ?? '',
                        'layanan'         => $p['Layanan Utama']            ?? '',
                        'kontak'          => $p['Kontak/Telp Darurat']      ?? '',
                        'status_aktif'    => $p['Status Aktif']             ?? '',
                        'longitude'       => $coords[0],
                        'latitude'        => $coords[1],
                    ]
                );
                $count++;
            }
            $this->info("Imported {$count} mitigasi records.");
        }

        $this->info('Import selesai!');
    }
}
