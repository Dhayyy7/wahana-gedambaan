<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KapalController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ScanController;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard (Semua role: Superadmin, Kasir, Operator)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kapal (Khusus Superadmin)
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('kapal', KapalController::class);
    });

    // Pemesanan / Tiket (Superadmin & Kasir)
    Route::middleware('role:superadmin,kasir')->group(function () {
        Route::get('tiket/{tiket}/print', [TiketController::class, 'print'])->name('tiket.print');
        Route::patch('tiket/{tiket}/cancel', [TiketController::class, 'cancel'])->name('tiket.cancel');
        Route::resource('tiket', TiketController::class);
    });

    // Scan Tiket (Superadmin & Operator)
    Route::middleware('role:superadmin,operator')->group(function () {
        Route::get('scan', [ScanController::class, 'index'])->name('scan.index');
        Route::post('scan/process', [ScanController::class, 'process'])->name('scan.process');
        Route::post('scan/selesai-trip/{kapal}', [ScanController::class, 'selesaiTrip'])->name('scan.selesai-trip');
    });

    // Manajemen Pengguna (Khusus Superadmin)
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Laporan Transaksi Bulanan (Superadmin & Kasir)
    Route::middleware('role:superadmin,kasir')->group(function () {
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
        Route::get('laporan/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.export-csv');
        Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    });
});

// Alias agar route('dashboard') tetap bekerja
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
