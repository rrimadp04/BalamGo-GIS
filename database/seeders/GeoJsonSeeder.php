<?php

namespace Database\Seeders;

use App\Models\Mitigasi;
use App\Models\Wisata;
use Illuminate\Database\Seeder;

class GeoJsonSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedWisata();
        $this->seedMitigasi();
    }

    private function seedWisata(): void
    {
        $geojson = $this->readGeoJson('layer_wisata.geojson');
        $names = [];

        foreach ($geojson['features'] ?? [] as $feature) {
            $p = $feature['properties'] ?? [];
            $coords = $feature['geometry']['coordinates'] ?? null;
            $name = $p['Nama Tempat Wisata'] ?? null;

            if (!$name || !is_array($coords) || count($coords) < 2) {
                continue;
            }

            $names[] = $name;

            Wisata::updateOrCreate(
                ['nama_wisata' => $name],
                [
                    'kategori'        => $p['Kategori'] ?? '',
                    'sub_kategori'    => $p['Sub-Kategori'] ?? '',
                    'alamat'          => $p['Alamat (sesuai sumber resmi)'] ?? $p['Alamat Lengkap'] ?? '',
                    'kelurahan'       => $p['Kelurahan'] ?? '',
                    'kecamatan'       => $p['Kecamatan'] ?? '',
                    'deskripsi'       => $p['Keterangan'] ?? '',
                    'jam_operasional' => $p['Jam Operasional'] ?? '',
                    'harga_tiket'     => $p['Harga Tiket (Rp)'] ?? '',
                    'fasilitas'       => $p['Fasilitas'] ?? $p['Fasilitas Tersedia'] ?? '',
                    'kapasitas'       => $p['Kapasitas Pengunjung'] ?? '',
                    'kontak'          => $p['Kontak'] ?? $p['Kontak/Telp'] ?? '',
                    'website'         => $p['Website/Instagram'] ?? '',
                    'longitude'       => $coords[0],
                    'latitude'        => $coords[1],
                ]
            );
        }

        if ($names !== []) {
            Wisata::whereNotIn('nama_wisata', $names)->delete();
        }
    }

    private function seedMitigasi(): void
    {
        $names = [];

        foreach (['layer_rumah_sakit.geojson', 'layer_mitigasi_bencana.geojson', 'layer_titik_evakuasi.geojson'] as $file) {
            $geojson = $this->readGeoJson($file);

            foreach ($geojson['features'] ?? [] as $feature) {
                $p = $feature['properties'] ?? [];
                $coords = $feature['geometry']['coordinates'] ?? null;
                $name = $p['Nama Fasilitas'] ?? $p['nama_lokasi'] ?? null;

                if (!$name || !is_array($coords) || count($coords) < 2) {
                    continue;
                }

                $names[] = $name;
                $jenis = $p['Jenis'] ?? $p['Jenis Fasilitas'] ?? '';
                $subJenis = $p['Sub Jenis'] ?? $p['Sub-Jenis'] ?? '';

                Mitigasi::updateOrCreate(
                    ['nama_lokasi' => $name],
                    [
                        'kategori'        => $jenis,
                        'jenis_fasilitas' => $jenis,
                        'sub_jenis'       => $subJenis,
                        'alamat'          => $p['Alamat (Google Maps)'] ?? $p['Alamat Lengkap'] ?? '',
                        'kelurahan'       => $p['Kelurahan'] ?? '',
                        'kecamatan'       => $p['Kecamatan'] ?? '',
                        'jam_operasional' => $p['Jam Operasional'] ?? '',
                        'kapasitas'       => $p['Kapasitas'] ?? $p['Kapasitas (Orang/Bed)'] ?? '',
                        'layanan'         => $p['Layanan Utama'] ?? '',
                        'kontak'          => $p['Kontak Darurat'] ?? $p['Kontak/Telp Darurat'] ?? '',
                        'status_aktif'    => $p['Status Aktif'] ?? 'Aktif',
                        'longitude'       => $coords[0],
                        'latitude'        => $coords[1],
                    ]
                );
            }
        }

        if ($names !== []) {
            Mitigasi::whereNotIn('nama_lokasi', $names)->delete();
        }
    }

    private function readGeoJson(string $file): array
    {
        $path = storage_path("app/public/geojson/{$file}");

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?: [];
    }
}
