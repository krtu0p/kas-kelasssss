<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

// Halaman siswa bisa diakses tanpa login
Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');

// Route untuk bendahara harus login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/update', [DashboardController::class, 'update'])->name('pembayaran.update');
    Route::post('/tambah-minggu', [DashboardController::class, 'tambahMinggu'])->name('pembayaran.tambah_minggu');
});

// Login dan logout untuk bendahara
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect default ke siswa
Route::get('/', function () {
    return redirect()->route('siswa.index');
});
