<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wisata', function (Blueprint $table) {
    $table->id();

    $table->string('nama_wisata');
    $table->string('kategori');

    $table->string('alamat')->nullable();

    $table->text('deskripsi')->nullable();

    $table->decimal('latitude', 10, 7);

    $table->decimal('longitude', 10, 7);

    $table->string('foto')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};
