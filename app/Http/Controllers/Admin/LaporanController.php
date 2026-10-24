<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Halaman laporan transaksi bulanan dengan filter periode, kapal, dan status.
     */
    public function index(Request $request): View
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $kapalId = $request->input('kapal_id', 'all');
        $status = $request->input('status', 'all');

        $baseQuery = Tiket::with('kapal')
            ->whereMonth('tanggal_pemesanan', $bulan)
            ->whereYear('tanggal_pemesanan', $tahun);

        if ($kapalId !== 'all' && $kapalId !== '' && $kapalId !== null) {
            $baseQuery->where('kapal_id', (int) $kapalId);
        }

        if ($status !== 'all' && $status !== '' && $status !== null) {
            $baseQuery->where('status', (int) $status);
        }

        // Summary metrics
        $statsQuery = clone $baseQuery;
        $totalTransaksi = (clone $statsQuery)->count();
        $totalPendapatan = (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('total_bayar');
        $totalDiskon = (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('diskon');
        $tiketSelesai = (clone $statsQuery)->where('status', Tiket::STATUS_SELESAI)->count();
        $tiketPending = (clone $statsQuery)->where('status', Tiket::STATUS_PENDING)->count();
        $tiketBatal = (clone $statsQuery)->where('status', Tiket::STATUS_BATAL)->count();

        // Data transaksi dipaginate 20 per halaman
        $tikets = (clone $baseQuery)
            ->orderByDesc('tanggal_pemesanan')
            ->paginate(20)
            ->withQueryString();

        $kapals = Kapal::orderBy('nama_kapal')->get(['id', 'nama_kapal', 'kode_kapal']);

        // Daftar tahun untuk opsi dropdown (dari tahun tertua tiket hingga tahun sekarang + 1)
        $tahunAwal = Tiket::min('tanggal_pemesanan');
        $tahunMin = $tahunAwal ? (int) date('Y', strtotime($tahunAwal)) : now()->year;
        $tahunMax = now()->year + 1;
        $daftarTahun = range(max($tahunMin, now()->year - 5), $tahunMax);

        return view('admin.laporan.index', compact(
            'tikets',
            'kapals',
            'bulan',
            'tahun',
            'kapalId',
            'status',
            'totalTransaksi',
            'totalPendapatan',
            'totalDiskon',
            'tiketSelesai',
            'tiketPending',
            'tiketBatal',
            'daftarTahun'
        ));
    }

    /**
     * Export laporan bulanan langsung ke format Microsoft Excel (.xls / SpreadsheetML)
     * lengkap dengan styling tabel, warna, border, lebar kolom, dan format angka mata uang.
     */
    public function exportExcel(Request $request): Response
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $kapalId = $request->input('kapal_id', 'all');
        $status = $request->input('status', 'all');

        $query = Tiket::with('kapal')
            ->whereMonth('tanggal_pemesanan', $bulan)
            ->whereYear('tanggal_pemesanan', $tahun);

        $filterParts = [];

        if ($kapalId !== 'all' && $kapalId !== '' && $kapalId !== null) {
            $query->where('kapal_id', (int) $kapalId);
            $kapal = Kapal::find($kapalId);
            if ($kapal) {
                $filterParts[] = "Speedboat: {$kapal->nama_kapal}";
            }
        }

        if ($status !== 'all' && $status !== '' && $status !== null) {
            $query->where('status', (int) $status);
            $statusText = match ((int) $status) {
                Tiket::STATUS_SELESAI => 'Sudah Digunakan',
                Tiket::STATUS_PENDING => 'Belum Digunakan',
                Tiket::STATUS_BATAL => 'Dibatalkan',
                default => 'Semua',
            };
            $filterParts[] = "Status: {$statusText}";
        }

        $filterDesc = implode(' • ', $filterParts);

        // Rekapitulasi KPI untuk ringkasan di atas tabel Excel
        $statsQuery = clone $query;
        $totalTransaksi = (clone $statsQuery)->count();
        $totalPendapatan = (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('total_bayar');
        $totalDiskon = (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('diskon');
        $tiketSelesai = (clone $statsQuery)->where('status', Tiket::STATUS_SELESAI)->count();
        $tiketPending = (clone $statsQuery)->where('status', Tiket::STATUS_PENDING)->count();
        $tiketBatal = (clone $statsQuery)->where('status', Tiket::STATUS_BATAL)->count();

        $tikets = $query->orderBy('tanggal_pemesanan', 'asc')->get();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $bulanStr = str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);
        $filename = "laporan-transaksi-gedambaan-{$tahun}-{$bulanStr}.xls";

        $xml = view('admin.laporan.excel', compact(
            'tikets',
            'bulan',
            'tahun',
            'bulanStr',
            'namaBulan',
            'filterDesc',
            'totalTransaksi',
            'totalPendapatan',
            'totalDiskon',
            'tiketSelesai',
            'tiketPending',
            'tiketBatal'
        ))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
        ]);
    }

    /**
     * Export laporan bulanan ke file CSV dengan separator titik koma (;) dan UTF-8 BOM
     * agar kompatibel dengan Excel pada regional setting Indonesia.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $kapalId = $request->input('kapal_id', 'all');
        $status = $request->input('status', 'all');

        $query = Tiket::with('kapal')
            ->whereMonth('tanggal_pemesanan', $bulan)
            ->whereYear('tanggal_pemesanan', $tahun);

        if ($kapalId !== 'all' && $kapalId !== '' && $kapalId !== null) {
            $query->where('kapal_id', (int) $kapalId);
        }

        if ($status !== 'all' && $status !== '' && $status !== null) {
            $query->where('status', (int) $status);
        }

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $bulanStr = str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);
        $filename = "laporan-transaksi-gedambaan-{$tahun}-{$bulanStr}.csv";

        return response()->streamDownload(function () use ($query, $bulan, $tahun, $namaBulan) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM untuk Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Petunjuk separator untuk Excel
            fwrite($handle, "sep=;\r\n");

            // Header informasi dokumen
            fputcsv($handle, ['LAPORAN TRANSAKSI WAHANA GEDAMBAAN'], ';');
            fputcsv($handle, ['Periode', ($namaBulan[$bulan] ?? $bulan).' '.$tahun], ';');
            fputcsv($handle, ['Waktu Download', now()->format('d/m/Y H:i:s')], ';');
            fputcsv($handle, [], ';');

            // Header tabel
            fputcsv($handle, [
                'No',
                'Kode Tiket',
                'Tanggal Pemesanan',
                'Nama Pembeli',
                'No HP',
                'Alamat',
                'Speedboat',
                'Tipe Hari',
                'Tarif (Rp)',
                'Diskon (Rp)',
                'Total Bayar (Rp)',
                'Status',
                'Waktu Scan/Boarding',
            ], ';');

            $no = 1;
            $query->orderBy('tanggal_pemesanan', 'asc')->chunk(200, function ($tikets) use ($handle, &$no) {
                foreach ($tikets as $tiket) {
                    $statusLabel = match ($tiket->status) {
                        Tiket::STATUS_BATAL => 'Dibatalkan',
                        Tiket::STATUS_PENDING => 'Belum Digunakan',
                        Tiket::STATUS_SELESAI => 'Sudah Digunakan',
                        default => 'Lainnya',
                    };

                    fputcsv($handle, [
                        $no++,
                        $tiket->kode_tiket,
                        $tiket->tanggal_pemesanan ? $tiket->tanggal_pemesanan->format('d/m/Y H:i') : '-',
                        $tiket->nama_pembeli,
                        $tiket->no_hp,
                        $tiket->alamat ?? '-',
                        $tiket->kapal ? $tiket->kapal->nama_kapal : '-',
                        $tiket->is_weekend ? 'Weekend' : 'Hari Biasa',
                        (int) $tiket->tarif,
                        (int) $tiket->diskon,
                        (int) $tiket->total_bayar,
                        $statusLabel,
                        $tiket->tanggal_scan ? $tiket->tanggal_scan->format('d/m/Y H:i') : '-',
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Endpoint umum export laporan (default langsung ke format Excel terformat).
     */
    public function export(Request $request): Response|StreamedResponse
    {
        if ($request->query('format') === 'csv') {
            return $this->exportCsv($request);
        }

        return $this->exportExcel($request);
    }
}
