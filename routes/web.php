<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelulusanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AdminGaleriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SiswaFotoController;
use App\Http\Controllers\FotoProfilImportController;
use App\Http\Controllers\KartuLoginController;
use App\Http\Controllers\Siswa\SiswaDashboardController;

// ── Halaman Publik ────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'))->name('home');
Route::post('/cek', [KelulusanController::class, 'cek'])->name('cek');
Route::get('/siswa/{siswa}/surat', [KelulusanController::class, 'cetakSurat'])->name('siswa.surat');

// ── Login Terpadu (Admin + Siswa) ─────────────────────────────────────────
Route::get('/login',   [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',  [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard',  [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/login-log',  [AdminController::class, 'loginLog'])->name('login-log');

    // CRUD Siswa
    Route::get('/siswa',                 [AdminController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/tambah',          [AdminController::class, 'create'])->name('siswa.create');
    Route::post('/siswa',                [AdminController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/import',          [AdminController::class, 'importForm'])->name('siswa.import-form');
    Route::post('/siswa/import',         [AdminController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/import-template', [AdminController::class, 'downloadTemplate'])->name('siswa.import-template');
    Route::get('/siswa/akun-template',   [AdminController::class, 'downloadAkunTemplate'])->name('siswa.akun-template');
    Route::post('/siswa/akun-import',    [AdminController::class, 'importAkun'])->name('siswa.akun-import');
    Route::post('/siswa/import-foto',    [FotoProfilImportController::class, 'import'])->name('siswa.import-foto');
    Route::get('/siswa/skl-massal',      [AdminController::class, 'sklMassal'])->name('siswa.skl-massal');
    Route::get('/siswa/{siswa}/edit',    [AdminController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{siswa}',         [AdminController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}',      [AdminController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/siswa/{siswa}/surat',   [AdminController::class, 'cetakSurat'])->name('siswa.surat-admin');
    Route::post('/siswa/{siswa}/akun',   [AdminController::class, 'kelolaAkun'])->name('siswa.akun');

    // Foto Profil Siswa
    Route::post('/siswa/{siswa}/foto',   [SiswaFotoController::class, 'upload'])->name('siswa.foto.upload');
    Route::delete('/siswa/{siswa}/foto', [SiswaFotoController::class, 'hapus'])->name('siswa.foto.hapus');

    // Pengaturan
    Route::get('/setting',             [SettingController::class, 'index'])->name('setting');
    Route::post('/setting',            [SettingController::class, 'update'])->name('setting.update');
    Route::post('/setting/hapus-logo', [SettingController::class, 'hapusLogo'])->name('setting.hapus-logo');

    // Galeri Momen
    Route::get('/galeri',            [AdminGaleriController::class, 'index'])->name('galeri');
    Route::delete('/galeri/{momen}', [AdminGaleriController::class, 'destroy'])->name('galeri.destroy');

    // Kartu Login Siswa
    Route::get('/kartu-login',       [KartuLoginController::class, 'index'])->name('kartu-login.index');
    Route::get('/kartu-login/cetak', [KartuLoginController::class, 'cetak'])->name('kartu-login.cetak');
});

// ── Siswa Portal ──────────────────────────────────────────────────────────
Route::prefix('siswa')->name('siswa.')->middleware('auth:siswa')->group(function () {
    Route::post('/logout',          [LoginController::class,          'logout'])->name('logout');
    Route::get('/dashboard',        [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/momen',           [SiswaDashboardController::class, 'uploadMomen'])->name('momen.upload');
    Route::delete('/momen/{momen}', [SiswaDashboardController::class, 'hapusMomen'])->name('momen.hapus');
    Route::get('/galeri',           [SiswaDashboardController::class, 'galeri'])->name('galeri');
});