<?php

use App\Http\Controllers\WisataController;
use App\Http\Controllers\MitigasiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmergencyReportController;
use Illuminate\Support\Facades\Route;


// Halaman publik
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/peta', function () {
    return view('peta');
})->name('peta');

Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/wisata/{wisata}', [WisataController::class, 'showPublic'])->name('wisata.show');
Route::get('/mitigasi/{mitigasi}', [MitigasiController::class, 'showPublic'])->name('mitigasi.show');

// API untuk Leaflet (return JSON)
Route::get('/api/wisata/search',   [WisataController::class,   'search']);
Route::get('/api/mitigasi/search', [MitigasiController::class, 'search']);
Route::get('/api/wisata',          [WisataController::class,   'apiIndex'])->name('api.wisata');
Route::get('/api/mitigasi',        [MitigasiController::class, 'apiIndex'])->name('api.mitigasi');

// Emergency reports (guest)
Route::post('/emergency-reports', [EmergencyReportController::class, 'store']);
Route::get('/api/emergency/captcha', [EmergencyReportController::class, 'captcha']);
Route::get('/api/emergency/nearby', [EmergencyReportController::class, 'nearby']);


// Route Admin (dilindungi auth)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('wisata',   WisataController::class);
    Route::resource('mitigasi', MitigasiController::class);
});

// Profile (dari Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (login/logout)
require __DIR__.'/auth.php';
