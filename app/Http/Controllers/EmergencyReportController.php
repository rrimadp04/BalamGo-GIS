<?php

namespace App\Http\Controllers;

use App\Models\EmergencyReport;
use App\Models\Mitigasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmergencyReportController extends Controller
{
    // POST: /emergency-reports
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:30',
            'jenis_kejadiaan' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:2000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            'captcha_answer' => 'required|numeric',
            'captcha_token' => 'required|string|max:255',
        ]);

        // Captcha sederhana: token berisi jawaban yang diharapkan (disimpan di session/token).
        // Implementasi aman sederhana untuk MVP: cocokkan captcha_answer dengan session.
        $expected = $request->session()->get('emergency_captcha_answer');
        $token = $request->session()->get('emergency_captcha_token');

        if (!$expected || !$token || !hash_equals((string)$token, (string)$request->input('captcha_token')) || (int)$expected !== (int)$request->input('captcha_answer')) {
            return response()->json([
                'message' => 'Captcha salah. Silakan coba lagi.',
            ], 422);
        }

        $fotoPath = $request->file('foto')->store('emergency-reports', 'public');

        $report = EmergencyReport::create([
            'nama' => $request->input('nama'),
            'no_hp' => $request->input('no_hp'),
            'jenis_kejadiaan' => $request->input('jenis_kejadiaan'),
            'deskripsi' => $request->input('deskripsi'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'foto_path' => $fotoPath,
            'captcha_answer' => (int)$request->input('captcha_answer'),
            'captcha_token' => $request->input('captcha_token'),
        ]);

        // Reset captcha agar tidak bisa re-submit
        $request->session()->forget('emergency_captcha_answer');
        $request->session()->forget('emergency_captcha_token');

        return response()->json([
            'message' => 'Laporan berhasil dikirim. Terima kasih!',
            'id' => $report->id,
        ], 201);
    }

    // GET: /api/emergency/nearby?lat=...&lng=...
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $lat = (float)$request->query('lat');
        $lng = (float)$request->query('lng');

        // Distance calc (Haversine) di PHP untuk MVP.
        // Data mitigasi berisi campuran: Rumah Sakit / BPBD / Damkar / Evakuasi.
        $all = Mitigasi::select(['id','nama_lokasi','kategori','alamat','kecamatan','latitude','longitude','kontak','status_aktif','kapasitas'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $rows = $all->map(function($m) use ($lat, $lng) {
            $d = $this->distanceKm($lat, $lng, (float)$m->latitude, (float)$m->longitude);
            return [$m, $d];
        });

        $pick = function($kategoriPred) use ($rows) {
            $filtered = $rows->filter(function($row) use ($kategoriPred) {
                [$m, $d] = $row;
                return $kategoriPred($m->kategori);
            });
            $sorted = $filtered->sortBy(fn($row) => $row[1]);
            return $sorted->first();
        };

        // Pemetaan kategori (sesuai label yang sudah dipakai di peta)
        $rs = $pick(fn($k) => in_array($k, ['Rumah Sakit','Fasilitas Kesehatan'], true));
        $bpbd = $pick(fn($k) => in_array($k, ['Lembaga Pemerintah','Dinas Pemerintah'], true));
        $evakuasi = $pick(fn($k) => in_array($k, ['Ruang Terbuka'], true));

        $format = function($row) {
            if (!$row) return null;
            /** @var \App\Models\Mitigasi $m */
            [$m, $d] = $row;
            return [
                'id' => $m->id,
                'nama_lokasi' => $m->nama_lokasi,
                'kategori' => $m->kategori,
                'alamat' => $m->alamat,
                'kecamatan' => $m->kecamatan,
                'latitude' => (float)$m->latitude,
                'longitude' => (float)$m->longitude,
                'kontak' => $m->kontak,
                'distance_km' => round($d, 2),
            ];
        };

        return response()->json([
            'rumah_sakit' => $format($rs),
            'bpbd' => $format($bpbd),
            'evakuasi' => $format($evakuasi),
        ]);
    }

    private function distanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    // GET: /api/emergency/captcha
    // untuk generate angka acak di server.
    public function captcha(Request $request)
    {
        $a = random_int(1, 15);
        $b = random_int(1, 15);
        $answer = $a + $b;
        $token = bin2hex(random_bytes(16));

        $request->session()->put('emergency_captcha_answer', $answer);
        $request->session()->put('emergency_captcha_token', $token);

        return response()->json([
            'a' => $a,
            'b' => $b,
            'token' => $token,
        ]);
    }
}

