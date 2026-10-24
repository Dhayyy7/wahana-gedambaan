<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\Tiket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard admin dengan metrik dinamis real-time.
     */
    public function index(): View
    {
        // 1. Stat Metrik Utama
        $totalPemesanan = Tiket::count();

        $pemesananMingguIni = Tiket::where('tanggal_pemesanan', '>=', now()->startOfWeek())->count();
        $pemesananMingguLalu = Tiket::whereBetween('tanggal_pemesanan', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();

        $persenPemesanan = $pemesananMingguLalu > 0
            ? round((($pemesananMingguIni - $pemesananMingguLalu) / $pemesananMingguLalu) * 100)
            : ($pemesananMingguIni > 0 ? 100 : 0);

        // Pelanggan Aktif / Unik
        $pelangganAktif = Tiket::distinct('nama_pembeli')->count('nama_pembeli');

        // Armada Kapal
        $totalKapal = Kapal::count();
        $kapalTersedia = Kapal::where('status', Kapal::STATUS_TERSEDIA)->count();
        $kapalBerlayar = Kapal::where('status', Kapal::STATUS_SEDANG_BERLAYAR)->count();
        $kapalMaintenance = Kapal::where('status', Kapal::STATUS_MAINTENANCE)->count();

        // Pendapatan Bulan Ini (hanya tiket yang tidak dibatalkan)
        $pendapatanBulanIni = Tiket::where('status', '!=', Tiket::STATUS_BATAL)
            ->whereMonth('tanggal_pemesanan', now()->month)
            ->whereYear('tanggal_pemesanan', now()->year)
            ->sum('total_bayar');

        $pendapatanBulanLalu = Tiket::where('status', '!=', Tiket::STATUS_BATAL)
            ->whereMonth('tanggal_pemesanan', now()->subMonth()->month)
            ->whereYear('tanggal_pemesanan', now()->subMonth()->year)
            ->sum('total_bayar');

        $persenPendapatan = $pendapatanBulanLalu > 0
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100)
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        // 2. Status Pemesanan
        $tiketPending = Tiket::where('status', Tiket::STATUS_PENDING)->count();
        $tiketSelesai = Tiket::where('status', Tiket::STATUS_SELESAI)->count();
        $tiketBatal = Tiket::where('status', Tiket::STATUS_BATAL)->count();

        // 3. Tren Pemesanan 7 Hari Terakhir
        $hariIni = now()->startOfDay();
        $tren7Hari = collect(range(6, 0))->map(function ($daysAgo) use ($hariIni) {
            $tgl = (clone $hariIni)->subDays($daysAgo);
            $count = Tiket::whereDate('tanggal_pemesanan', $tgl->toDateString())->count();
            $pendapatan = Tiket::where('status', '!=', Tiket::STATUS_BATAL)
                ->whereDate('tanggal_pemesanan', $tgl->toDateString())
                ->sum('total_bayar');

            return [
                'tanggal' => $tgl->format('d M'),
                'hari' => $tgl->translatedFormat('D'),
                'count' => $count,
                'pendapatan' => $pendapatan,
            ];
        });

        $maxCount = max(5, $tren7Hari->max('count') ?? 5);

        // 4. Pemesanan Terbaru (5 Transaksi Terakhir)
        $pemesananTerbaru = Tiket::with('kapal')
            ->latest('tanggal_pemesanan')
            ->take(5)
            ->get();

        // 5. Armada Kapal Ringkasan
        $kapals = Kapal::orderBy('nama_kapal')->take(4)->get();

        return view('admin.dashboard.index', compact(
            'totalPemesanan',
            'pemesananMingguIni',
            'persenPemesanan',
            'pelangganAktif',
            'totalKapal',
            'kapalTersedia',
            'kapalBerlayar',
            'kapalMaintenance',
            'pendapatanBulanIni',
            'persenPendapatan',
            'tiketPending',
            'tiketSelesai',
            'tiketBatal',
            'tren7Hari',
            'maxCount',
            'pemesananTerbaru',
            'kapals'
        ));
    }
}
