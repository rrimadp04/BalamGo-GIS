<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_reports', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('jenis_kejadiaan');
            $table->text('deskripsi');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('foto_path');
            $table->integer('captcha_answer')->nullable();
            $table->string('captcha_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_reports');
    }
};

