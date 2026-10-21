<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KapalController;
use App\Http\Controllers\Admin\ScanController;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kapal
    Route::resource('kapal', KapalController::class);

    // Pemesanan / Tiket
    Route::get('tiket/{tiket}/print', [TiketController::class, 'print'])->name('tiket.print');
    Route::resource('tiket', TiketController::class);

    // Scan Tiket (Operator Wahana)
    Route::get('scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('scan/process', [ScanController::class, 'process'])->name('scan.process');
    Route::post('scan/selesai-trip/{kapal}', [ScanController::class, 'selesaiTrip'])->name('scan.selesai-trip');

    // Manajemen Pengguna (Users: Superadmin, Kasir, Operator)
    Route::resource('users', UserController::class);
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
