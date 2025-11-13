<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PerawatController;
use App\Http\Controllers\ResepsionisController;
use App\Http\Controllers\PemilikController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;


Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', fn() => view('pages.home'))->name('home');
Route::get('/layanan', fn() => view('pages.layanan'))->name('layanan');
Route::get('/visi-misi', fn() => view('pages.visi'))->name('visi.misi');
Route::get('/struktur-organisasi', fn() => view('pages.struktur-organisasi'))->name('struktur.organisasi');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('login', [AuthController::class, 'login'])->name('login.process');

// Admin Routes
Route::middleware(['auth', 'role:Administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('kategori', App\Http\Controllers\Admin\KategoriController::class);
    Route::resource('jenis-hewan', App\Http\Controllers\Admin\JenisHewanController::class);
    Route::resource('ras-hewan', App\Http\Controllers\Admin\RasHewanController::class);
    Route::get('/ras-hewan/by-jenis/{jenisHewanId}', [App\Http\Controllers\Admin\RasHewanController::class, 'getByJenis'])->name('ras-hewan.by-jenis');
    Route::resource('pemilik', App\Http\Controllers\Admin\PemilikController::class);
    Route::get('/pemilik/get-pemilik-data/{id}', [App\Http\Controllers\Admin\PemilikController::class, 'getPemilikData'])->name('pemilik.getPemilikData');
    Route::resource('pet', App\Http\Controllers\Admin\PetController::class);
    Route::get('/pet/get-pet-data/{id}', [App\Http\Controllers\Admin\PetController::class, 'getPetData'])->name('pet.getPetData');
    Route::resource('kategori-klinis', App\Http\Controllers\Admin\KategoriKlinisController::class);
    Route::resource('kode-tindakan-terapi', App\Http\Controllers\Admin\KodeTindakanTerapiController::class);
});

// Dokter Routes
Route::middleware(['auth', 'role:Dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal-pemeriksaan', [DokterController::class, 'jadwalPemeriksaan'])->name('jadwal-pemeriksaan');
    Route::get('/riwayat-rekam-medis', [DokterController::class, 'riwayatRekamMedis'])->name('riwayat-rekam-medis');
    Route::get('/tambah-rekam-medis/{idreservasi}', [DokterController::class, 'tambahRekamMedis'])->name('tambah-rekam-medis');
    Route::post('/rekam-medis', [DokterController::class, 'storeRekamMedis'])->name('store-rekam-medis');
    Route::post('/detail-rekam-medis', [DokterController::class, 'storeDetailRekamMedis'])->name('store-detail-rekam-medis');
    Route::delete('/detail-rekam-medis/{id}', [DokterController::class, 'destroyDetailRekamMedis'])->name('destroy-detail-rekam-medis');
});

// Perawat Routes
Route::middleware(['auth', 'role:Perawat'])->prefix('perawat')->name('perawat.')->group(function () {
    Route::get('/dashboard', [PerawatController::class, 'dashboard'])->name('dashboard');
    Route::get('/rekam-medis', [PerawatController::class, 'rekamMedis'])->name('rekam-medis');
    Route::get('/rekam-medis/{id}/detail', [PerawatController::class, 'detailRekamMedis'])->name('detail-rekam-medis');
    Route::post('/rekam-medis-utama', [PerawatController::class, 'storeRekamMedisUtama'])->name('store-rekam-medis-utama');
    Route::post('/detail-rekam-medis', [PerawatController::class, 'storeDetailRekamMedis'])->name('store-detail-rekam-medis');
    Route::delete('/detail-rekam-medis/{id}', [PerawatController::class, 'destroyDetailRekamMedis'])->name('destroy-detail-rekam-medis');
});

// Resepsionis Routes
Route::middleware(['auth', 'role:Resepsionis'])->prefix('resepsionis')->name('resepsionis.')->group(function () {
    Route::get('/dashboard', [ResepsionisController::class, 'dashboard'])->name('dashboard');
    Route::get('/registrasi-pemilik', [ResepsionisController::class, 'registrasiPemilik'])->name('registrasi-pemilik');
    Route::get('/registrasi-pet', [ResepsionisController::class, 'registrasiPet'])->name('registrasi-pet');
    Route::get('/temu-dokter', [ResepsionisController::class, 'temuDokter'])->name('temu-dokter');
    Route::get('/manajemen-rekam-medis', [ResepsionisController::class, 'manajemenRekamMedis'])->name('manajemen-rekam-medis');
    Route::post('/temu-dokter', [ResepsionisController::class, 'storeTemuDokter'])->name('store-temu-dokter');
    Route::post('/pemilik', [ResepsionisController::class, 'storePemilik'])->name('store-pemilik');
    Route::post('/pet', [ResepsionisController::class, 'storePet'])->name('store-pet');
});

// Pemilik Routes
Route::middleware(['auth', 'role:Pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
    Route::get('/daftar-pet', [PemilikController::class, 'daftarPet'])->name('daftar-pet');
    Route::get('/daftar-rekam-medis', [PemilikController::class, 'daftarRekamMedis'])->name('daftar-rekam-medis');
    Route::get('/daftar-reservasi', [PemilikController::class, 'daftarReservasi'])->name('daftar-reservasi');
    Route::get('/profil', [PemilikController::class, 'profil'])->name('profil');
    Route::get('/edit-profil', [PemilikController::class, 'editProfil'])->name('edit-profil');
    Route::post('/update-profil', [PemilikController::class, 'updateProfil'])->name('update-profil');
    Route::get('/rekam-medis/{id}/detail', [PemilikController::class, 'detailRekamMedis'])->name('detail-rekam-medis');
    Route::get('/pet/{id}/detail', [PemilikController::class, 'detailPet'])->name('detail-pet');
});
