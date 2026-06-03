<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitigasi', function (Blueprint $table) {
            $table->string('jenis_fasilitas', 100)->nullable()->after('kategori');
            $table->string('sub_jenis', 100)->nullable()->after('jenis_fasilitas');
            $table->string('kelurahan', 100)->nullable()->after('alamat');
            $table->string('kecamatan', 100)->nullable()->after('kelurahan');
            $table->string('jam_operasional', 100)->nullable()->after('kecamatan');
            $table->string('kapasitas', 100)->nullable()->after('jam_operasional');
            $table->text('layanan')->nullable()->after('kapasitas');
            $table->string('kontak', 100)->nullable()->after('layanan');
            $table->string('status_aktif', 50)->nullable()->after('kontak');
        });
    }

    public function down(): void
    {
        Schema::table('mitigasi', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_fasilitas', 'sub_jenis', 'kelurahan', 'kecamatan',
                'jam_operasional', 'kapasitas', 'layanan', 'kontak', 'status_aktif',
            ]);
        });
    }
};
