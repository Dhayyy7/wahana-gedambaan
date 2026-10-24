<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard dengan metrik dinamis yang disesuaikan berdasarkan peran (role).
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Metrik Umum & Superadmin
        $totalPemesanan = Tiket::count();
        $pemesananMingguIni = Tiket::where('tanggal_pemesanan', '>=', now()->startOfWeek())->count();
        $pemesananMingguLalu = Tiket::whereBetween('tanggal_pemesanan', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();

        $persenPemesanan = $pemesananMingguLalu > 0
            ? round((($pemesananMingguIni - $pemesananMingguLalu) / $pemesananMingguLalu) * 100)
            : ($pemesananMingguIni > 0 ? 100 : 0);

        $pelangganAktif = Tiket::distinct('nama_pembeli')->count('nama_pembeli');

        $totalKapal = Kapal::count();
        $kapalTersedia = Kapal::where('status', Kapal::STATUS_TERSEDIA)->count();
        $kapalBerlayar = Kapal::where('status', Kapal::STATUS_SEDANG_BERLAYAR)->count();
        $kapalMaintenance = Kapal::where('status', Kapal::STATUS_MAINTENANCE)->count();

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

        $tiketPending = Tiket::where('status', Tiket::STATUS_PENDING)->count();
        $tiketSelesai = Tiket::where('status', Tiket::STATUS_SELESAI)->count();
        $tiketBatal = Tiket::where('status', Tiket::STATUS_BATAL)->count();

        // Tren 7 Hari
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
        $pemesananTerbaru = Tiket::with('kapal')
            ->latest('tanggal_pemesanan')
            ->take(5)
            ->get();
        $kapals = Kapal::orderBy('nama_kapal')->take(4)->get();

        // 2. Metrik Khusus Kasir
        $tiketHariIni = Tiket::whereDate('tanggal_pemesanan', today())
            ->where('status', '!=', Tiket::STATUS_BATAL)
            ->count();

        $pendapatanHariIni = Tiket::whereDate('tanggal_pemesanan', today())
            ->where('status', '!=', Tiket::STATUS_BATAL)
            ->sum('total_bayar');

        $pendapatanKemarin = Tiket::whereDate('tanggal_pemesanan', today()->subDay())
            ->where('status', '!=', Tiket::STATUS_BATAL)
            ->sum('total_bayar');

        $persenPendapatanHariIni = $pendapatanKemarin > 0
            ? round((($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100)
            : ($pendapatanHariIni > 0 ? 100 : 0);

        $tiketPendingHariIni = Tiket::whereDate('tanggal_pemesanan', today())
            ->where('status', Tiket::STATUS_PENDING)
            ->count();

        $tiketSelesaiHariIni = Tiket::whereDate('tanggal_pemesanan', today())
            ->where('status', Tiket::STATUS_SELESAI)
            ->count();

        $tiketBatalHariIni = Tiket::whereDate('tanggal_pemesanan', today())
            ->where('status', Tiket::STATUS_BATAL)
            ->count();

        // 3. Metrik Khusus Operator
        $scannedHariIni = Tiket::where('status', Tiket::STATUS_SELESAI)
            ->where(function ($q) {
                $q->whereDate('tanggal_scan', today())
                    ->orWhere(function ($sub) {
                        $sub->whereNull('tanggal_scan')
                            ->whereDate('updated_at', today());
                    });
            })
            ->count();

        $allKapals = Kapal::orderBy('nama_kapal')->get();

        $recentScans = Tiket::with('kapal')
            ->where('status', Tiket::STATUS_SELESAI)
            ->orderByRaw('COALESCE(tanggal_scan, updated_at) DESC')
            ->take(6)
            ->get();

        $kapalBerlayarList = Kapal::where('status', Kapal::STATUS_SEDANG_BERLAYAR)
            ->orderBy('waktu_mulai_trip', 'desc')
            ->get();

        return view('admin.dashboard.index', compact(
            'user',
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
            'kapals',
            // Kasir
            'tiketHariIni',
            'pendapatanHariIni',
            'persenPendapatanHariIni',
            'tiketPendingHariIni',
            'tiketSelesaiHariIni',
            'tiketBatalHariIni',
            // Operator
            'scannedHariIni',
            'allKapals',
            'recentScans',
            'kapalBerlayarList'
        ));
    }
}
