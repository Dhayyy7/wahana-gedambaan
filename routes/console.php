<?php

use App\Models\Kapal;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Command manual untuk mereset kapal yang telah berlayar >= 30 menit.
 */
Artisan::command('kapal:reset-trip', function () {
    $kapals = Kapal::where('status', Kapal::STATUS_SEDANG_BERLAYAR)
        ->whereNotNull('waktu_mulai_trip')
        ->where('waktu_mulai_trip', '<=', now()->subMinutes(30))
        ->get();

    foreach ($kapals as $kapal) {
        $kapal->update([
            'status' => Kapal::STATUS_TERSEDIA,
            'waktu_mulai_trip' => null,
        ]);
        $this->info("Kapal {$kapal->nama_kapal} ({$kapal->kode_kapal}) selesai berlayar -> Tersedia.");
    }

    $this->info('Pengecekan trip kapal selesai. Total '.$kapals->count().' kapal di-reset ke Tersedia.');
})->purpose('Reset status kapal yang telah berlayar >= 30 menit kembali ke status Tersedia');

/**
 * Cron Job / Scheduler: Dijalankan setiap menit untuk mengecek kapal
 * yang sudah berlayar 30 menit agar statusnya otomatis kembali Tersedia (1).
 */
Schedule::call(function () {
    $updated = Kapal::where('status', Kapal::STATUS_SEDANG_BERLAYAR)
        ->whereNotNull('waktu_mulai_trip')
        ->where('waktu_mulai_trip', '<=', now()->subMinutes(30))
        ->update([
            'status' => Kapal::STATUS_TERSEDIA,
            'waktu_mulai_trip' => null,
        ]);

    if ($updated > 0) {
        Log::info("Cron Job: {$updated} kapal selesai berlayar (30 menit) dan otomatis berstatus Tersedia.");
    }
})->everyMinute()->name('reset-kapal-trip');
