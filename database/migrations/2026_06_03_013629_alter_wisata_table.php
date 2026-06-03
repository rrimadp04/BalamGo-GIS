<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wisata', function (Blueprint $table) {
            $table->string('sub_kategori', 100)->nullable()->after('kategori');
            $table->string('kelurahan', 100)->nullable()->after('alamat');
            $table->string('kecamatan', 100)->nullable()->after('kelurahan');
            $table->string('jam_operasional', 100)->nullable()->after('kecamatan');
            $table->string('harga_tiket', 100)->nullable()->after('jam_operasional');
            $table->text('fasilitas')->nullable()->after('harga_tiket');
            $table->string('kapasitas', 50)->nullable()->after('fasilitas');
            $table->string('kontak', 100)->nullable()->after('kapasitas');
            $table->string('website', 100)->nullable()->after('kontak');
        });
    }

    public function down(): void
    {
        Schema::table('wisata', function (Blueprint $table) {
            $table->dropColumn([
                'sub_kategori', 'kelurahan', 'kecamatan',
                'jam_operasional', 'harga_tiket', 'fasilitas',
                'kapasitas', 'kontak', 'website',
            ]);
        });
    }
};
