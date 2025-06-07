<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KeuanganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;

// Halaman siswa bisa diakses tanpa login
Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');

// Route untuk bendahara harus login
Route::middleware('auth')->group(function () {
    // Dashboard dan manajemen pembayaran
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/pembayaran/update', [DashboardController::class, 'update'])->name('pembayaran.update');
    Route::post('/pembayaran/tambah-minggu', [DashboardController::class, 'tambahMinggu'])->name('pembayaran.tambah_minggu');
    Route::post('/pembayaran/hapus-minggu', [DashboardController::class, 'hapusMinggu'])->name('pembayaran.hapus_minggu');
    Route::post('/dashboard/tambah-bulan', [DashboardController::class, 'tambahBulan'])->name('dashboard.tambahBulan');

    // Pengeluaran
    Route::get('/pengeluaran', [KeuanganController::class, 'pengeluaran'])->name('pengeluaran');
    Route::get('/pengeluaran/create', [KeuanganController::class, 'pengeluaranCreate'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [KeuanganController::class, 'pengeluaranStore'])->name('pengeluaran.store');
    Route::get('/pengeluaran/{id}/edit', [KeuanganController::class, 'pengeluaranEdit'])->name('pengeluaran.edit');
    Route::put('/pengeluaran/{id}', [KeuanganController::class, 'pengeluaranUpdate'])->name('pengeluaran.update');
    Route::delete('/pengeluaran/{id}', [KeuanganController::class, 'pengeluaranDestroy'])->name('pengeluaran.destroy');

    // Pemasukan
    Route::get('/pemasukan', [KeuanganController::class, 'pemasukan'])->name('pemasukan');
    Route::get('/pemasukan/create', [KeuanganController::class, 'pemasukanCreate'])->name('pemasukan.create');
    Route::post('/pemasukan', [KeuanganController::class, 'pemasukanStore'])->name('pemasukan.store');
    Route::get('/pemasukan/{id}/edit', [KeuanganController::class, 'pemasukanEdit'])->name('pemasukan.edit');
    Route::put('/pemasukan/{id}', [KeuanganController::class, 'pemasukanUpdate'])->name('pemasukan.update');
    Route::delete('/pemasukan/{id}', [KeuanganController::class, 'pemasukanDestroy'])->name('pemasukan.destroy');

    // Chart Keuangan
    Route::get('/chart', [ChartController::class, 'index'])->name('chart.index');
});

// Login dan logout untuk bendahara
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect default ke siswa
Route::get('/', function () {
    return redirect()->route('siswa.index');
});