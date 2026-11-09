<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\Pengeluaran;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Halaman laporan transaksi dengan filter rentang tanggal, wahana, pembayaran, dan status.
     */
    public function index(Request $request): View
    {
        Tiket::syncExpiredTickets();

        $kapalId = $request->input('kapal_id', 'all');
        $status = $request->input('status', 'all');
        $metodePembayaran = $request->input('metode_pembayaran', 'all');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if ($tanggalMulai && $tanggalSelesai && $tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [$tanggalSelesai, $tanggalMulai];
        }

        $baseQuery = Tiket::with('kapal');

        if (! empty($tanggalMulai)) {
            $baseQuery->whereDate('tanggal_pemesanan', '>=', $tanggalMulai);
        }

        if (! empty($tanggalSelesai)) {
            $baseQuery->whereDate('tanggal_pemesanan', '<=', $tanggalSelesai);
        }

        if ($kapalId !== 'all' && $kapalId !== '' && $kapalId !== null) {
            $baseQuery->where('kapal_id', (int) $kapalId);
        }

        if ($status !== 'all' && $status !== '' && $status !== null) {
            $baseQuery->where('status', (int) $status);
        }

        if ($metodePembayaran === 'kosong') {
            $baseQuery->whereNull('metode_pembayaran');
        } elseif (in_array($metodePembayaran, [Tiket::METODE_TUNAI, Tiket::METODE_QRIS], true)) {
            $baseQuery->where('metode_pembayaran', $metodePembayaran);
        }

        // Summary metrics
        $statsQuery = clone $baseQuery;
        $totalTransaksi = (clone $statsQuery)->count();
        $totalPendapatan = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('total_bayar');
        $pendapatanTunai = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->where('metode_pembayaran', Tiket::METODE_TUNAI)->sum('total_bayar');
        $pendapatanQris = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->where('metode_pembayaran', Tiket::METODE_QRIS)->sum('total_bayar');
        $totalDiskon = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('diskon');
        $tiketSelesai = (clone $statsQuery)->where('status', Tiket::STATUS_SELESAI)->count();
        $tiketPending = (clone $statsQuery)->where('status', Tiket::STATUS_PENDING)->count();
        $tiketBatal = (clone $statsQuery)->where('status', Tiket::STATUS_BATAL)->count();

        // Rekap pengeluaran periode terpilih & laba bersih
        $pengeluaranQuery = Pengeluaran::query();
        if (! empty($tanggalMulai)) {
            $pengeluaranQuery->whereDate('tanggal_pengeluaran', '>=', $tanggalMulai);
        }
        if (! empty($tanggalSelesai)) {
            $pengeluaranQuery->whereDate('tanggal_pengeluaran', '<=', $tanggalSelesai);
        }
        $totalPengeluaran = (float) $pengeluaranQuery->sum('nominal');
        $labaBersih = $totalPendapatan - $totalPengeluaran;

        // Data transaksi dipaginate 20 per halaman
        $tikets = (clone $baseQuery)
            ->orderByDesc('tanggal_pemesanan')
            ->paginate(20)
            ->withQueryString();

        $kapals = Kapal::orderBy('nama_kapal')->get(['id', 'nama_kapal', 'kode_kapal']);

        return view('admin.laporan.index', compact(
            'tikets',
            'kapals',
            'kapalId',
            'status',
            'metodePembayaran',
            'tanggalMulai',
            'tanggalSelesai',
            'totalTransaksi',
            'totalPendapatan',
            'pendapatanTunai',
            'pendapatanQris',
            'totalPengeluaran',
            'labaBersih',
            'totalDiskon',
            'tiketSelesai',
            'tiketPending',
            'tiketBatal'
        ));
    }

    /**
     * Export laporan langsung ke format Microsoft Excel (.xls / SpreadsheetML)
     * lengkap dengan styling tabel, warna, border, lebar kolom, dan format angka mata uang.
     */
    public function exportExcel(Request $request): Response
    {
        Tiket::syncExpiredTickets();

        $kapalId = $request->input('kapal_id', 'all');
        $status = $request->input('status', 'all');
        $metodePembayaran = $request->input('metode_pembayaran', 'all');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if ($tanggalMulai && $tanggalSelesai && $tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [$tanggalSelesai, $tanggalMulai];
        }

        $query = Tiket::with('kapal');

        $filterParts = [];

        if (! empty($tanggalMulai) || ! empty($tanggalSelesai)) {
            if (! empty($tanggalMulai)) {
                $query->whereDate('tanggal_pemesanan', '>=', $tanggalMulai);
            }
            if (! empty($tanggalSelesai)) {
                $query->whereDate('tanggal_pemesanan', '<=', $tanggalSelesai);
            }
            $startFmt = ! empty($tanggalMulai) ? Carbon::parse($tanggalMulai)->format('d M Y') : 'Awal';
            $endFmt = ! empty($tanggalSelesai) ? Carbon::parse($tanggalSelesai)->format('d M Y') : 'Sekarang';
            $periodeLabel = "{$startFmt} s/d {$endFmt}";
        } else {
            $periodeLabel = 'Semua Periode';
        }

        if ($kapalId !== 'all' && $kapalId !== '' && $kapalId !== null) {
            $query->where('kapal_id', (int) $kapalId);
            $kapal = Kapal::find($kapalId);
            if ($kapal) {
                $filterParts[] = "Wahana: {$kapal->nama_kapal}";
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

        if ($metodePembayaran === 'kosong') {
            $query->whereNull('metode_pembayaran');
            $filterParts[] = 'Pembayaran: Belum Diisi';
        } elseif (in_array($metodePembayaran, [Tiket::METODE_TUNAI, Tiket::METODE_QRIS], true)) {
            $query->where('metode_pembayaran', $metodePembayaran);
            $filterParts[] = 'Pembayaran: '.strtoupper($metodePembayaran);
        }

        $filterDesc = implode(' • ', $filterParts);

        // Rekapitulasi KPI untuk ringkasan di atas tabel Excel
        $statsQuery = clone $query;
        $totalTransaksi = (clone $statsQuery)->count();
        $totalPendapatan = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('total_bayar');
        $pendapatanTunai = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->where('metode_pembayaran', Tiket::METODE_TUNAI)->sum('total_bayar');
        $pendapatanQris = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->where('metode_pembayaran', Tiket::METODE_QRIS)->sum('total_bayar');
        $totalDiskon = (float) (clone $statsQuery)->where('status', '!=', Tiket::STATUS_BATAL)->sum('diskon');
        $tiketSelesai = (clone $statsQuery)->where('status', Tiket::STATUS_SELESAI)->count();
        $tiketPending = (clone $statsQuery)->where('status', Tiket::STATUS_PENDING)->count();
        $tiketBatal = (clone $statsQuery)->where('status', Tiket::STATUS_BATAL)->count();

        $pengeluaranQuery = Pengeluaran::with('user');
        if (! empty($tanggalMulai)) {
            $pengeluaranQuery->whereDate('tanggal_pengeluaran', '>=', $tanggalMulai);
        }
        if (! empty($tanggalSelesai)) {
            $pengeluaranQuery->whereDate('tanggal_pengeluaran', '<=', $tanggalSelesai);
        }
        $pengeluarans = $pengeluaranQuery
            ->orderBy('tanggal_pengeluaran', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $totalPengeluaran = (float) $pengeluarans->sum('nominal');
        $labaBersih = $totalPendapatan - $totalPengeluaran;

        $tikets = $query->orderBy('tanggal_pemesanan', 'asc')->get();

        $bulan = now()->month;
        $tahun = now()->year;
        $bulanStr = now()->format('m');
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $filename = 'laporan-transaksi-gedambaan-'.now()->format('Ymd').'.xls';

        $xml = view('admin.laporan.excel', compact(
            'tikets',
            'pengeluarans',
            'bulan',
            'tahun',
            'bulanStr',
            'namaBulan',
            'periodeLabel',
            'filterDesc',
            'totalTransaksi',
            'totalPendapatan',
            'pendapatanTunai',
            'pendapatanQris',
            'totalPengeluaran',
            'labaBersih',
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
     * Export laporan ke file CSV dengan separator titik koma (;) dan UTF-8 BOM
     * agar kompatibel dengan Excel pada regional setting Indonesia.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        Tiket::syncExpiredTickets();

        $kapalId = $request->input('kapal_id', 'all');
        $status = $request->input('status', 'all');
        $metodePembayaran = $request->input('metode_pembayaran', 'all');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if ($tanggalMulai && $tanggalSelesai && $tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [$tanggalSelesai, $tanggalMulai];
        }

        $query = Tiket::with('kapal');

        if (! empty($tanggalMulai) || ! empty($tanggalSelesai)) {
            if (! empty($tanggalMulai)) {
                $query->whereDate('tanggal_pemesanan', '>=', $tanggalMulai);
            }
            if (! empty($tanggalSelesai)) {
                $query->whereDate('tanggal_pemesanan', '<=', $tanggalSelesai);
            }
            $startFmt = ! empty($tanggalMulai) ? Carbon::parse($tanggalMulai)->format('d M Y') : 'Awal';
            $endFmt = ! empty($tanggalSelesai) ? Carbon::parse($tanggalSelesai)->format('d M Y') : 'Sekarang';
            $periodeLabel = "{$startFmt} s/d {$endFmt}";
        } else {
            $periodeLabel = 'Semua Periode';
        }

        if ($kapalId !== 'all' && $kapalId !== '' && $kapalId !== null) {
            $query->where('kapal_id', (int) $kapalId);
        }

        if ($status !== 'all' && $status !== '' && $status !== null) {
            $query->where('status', (int) $status);
        }

        if ($metodePembayaran === 'kosong') {
            $query->whereNull('metode_pembayaran');
        } elseif (in_array($metodePembayaran, [Tiket::METODE_TUNAI, Tiket::METODE_QRIS], true)) {
            $query->where('metode_pembayaran', $metodePembayaran);
        }

        $filename = 'laporan-transaksi-gedambaan-'.now()->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($query, $periodeLabel) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM untuk Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Petunjuk separator untuk Excel
            fwrite($handle, "sep=;\r\n");

            // Header informasi dokumen
            fputcsv($handle, ['LAPORAN TRANSAKSI WAHANA GEDAMBAAN'], ';');
            fputcsv($handle, ['Periode', $periodeLabel], ';');
            fputcsv($handle, ['Waktu Download', now()->format('d/m/Y H:i:s').' WIB'], ';');
            fputcsv($handle, [], ';');

            // Header tabel
            fputcsv($handle, [
                'No',
                'Kode Tiket',
                'Tanggal Pemesanan',
                'Nama Pembeli',
                'No HP',
                'Alamat',
                'Wahana',
                'Tipe Hari',
                'Tarif (Rp)',
                'Diskon (Rp)',
                'Total Bayar (Rp)',
                'Metode Pembayaran',
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
                        $tiket->metode_pembayaran_label,
                        $statusLabel,
                        $tiket->tanggal_scan
                            ? $tiket->tanggal_scan->format('d/m/Y H:i')
                            : ($tiket->isAutoSelesai() ? 'Auto 12 Jam (Manual)' : '-'),
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
