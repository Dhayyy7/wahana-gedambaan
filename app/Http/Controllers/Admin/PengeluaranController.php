<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePengeluaranRequest;
use App\Http\Requests\Admin\UpdatePengeluaranRequest;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PengeluaranController extends Controller
{
    /**
     * Daftar catatan pengeluaran dengan filter rentang tanggal dan pencarian.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if ($tanggalMulai && $tanggalSelesai && $tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [$tanggalSelesai, $tanggalMulai];
        }

        $baseQuery = Pengeluaran::with('user');

        if (! empty($tanggalMulai)) {
            $baseQuery->whereDate('tanggal_pengeluaran', '>=', $tanggalMulai);
        }

        if (! empty($tanggalSelesai)) {
            $baseQuery->whereDate('tanggal_pengeluaran', '<=', $tanggalSelesai);
        }

        if ($request->filled('search')) {
            $keyword = trim((string) $search);
            $baseQuery->where(function ($q) use ($keyword) {
                $q->where('kode_pengeluaran', 'like', "%{$keyword}%")
                    ->orWhere('nama_pengeluaran', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        $statsQuery = clone $baseQuery;
        $totalCatatan = (clone $statsQuery)->count();
        $totalPengeluaran = (float) (clone $statsQuery)->sum('nominal');
        $pengeluaranTerbesar = (float) ((clone $statsQuery)->max('nominal') ?? 0);
        $pengeluaranHariIni = (float) Pengeluaran::whereDate('tanggal_pengeluaran', today())->sum('nominal');

        $pengeluarans = (clone $baseQuery)
            ->orderByDesc('tanggal_pengeluaran')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pengeluaran.index', compact(
            'pengeluarans',
            'search',
            'tanggalMulai',
            'tanggalSelesai',
            'totalCatatan',
            'totalPengeluaran',
            'pengeluaranTerbesar',
            'pengeluaranHariIni'
        ));
    }

    /**
     * Form tambah catatan pengeluaran baru.
     */
    public function create(): View
    {
        return view('admin.pengeluaran.create');
    }

    /**
     * Simpan catatan pengeluaran baru.
     */
    public function store(StorePengeluaranRequest $request): RedirectResponse
    {
        $pengeluaran = Pengeluaran::create([
            'kode_pengeluaran' => Pengeluaran::generateKodePengeluaran($request->tanggal_pengeluaran),
            'user_id' => auth()->id(),
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('admin.pengeluaran.index')
            ->with('success', "Catatan pengeluaran {$pengeluaran->kode_pengeluaran} ({$pengeluaran->nama_pengeluaran}) berhasil disimpan.");
    }

    /**
     * Redirect detail ke halaman edit.
     */
    public function show(Pengeluaran $pengeluaran): RedirectResponse
    {
        return redirect()->route('admin.pengeluaran.edit', $pengeluaran);
    }

    /**
     * Form edit catatan pengeluaran.
     */
    public function edit(Pengeluaran $pengeluaran): View
    {
        return view('admin.pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * Perbarui catatan pengeluaran.
     */
    public function update(UpdatePengeluaranRequest $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        $pengeluaran->update([
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('admin.pengeluaran.index')
            ->with('success', "Catatan pengeluaran {$pengeluaran->kode_pengeluaran} berhasil diperbarui.");
    }

    /**
     * Hapus catatan pengeluaran (khusus Superadmin).
     */
    public function destroy(Pengeluaran $pengeluaran): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->isSuperadmin()) {
            return back()->withErrors(['error' => 'Hanya Superadmin yang diizinkan menghapus catatan pengeluaran.']);
        }

        $kode = $pengeluaran->kode_pengeluaran;
        $nama = $pengeluaran->nama_pengeluaran;
        $pengeluaran->delete();

        return redirect()
            ->route('admin.pengeluaran.index')
            ->with('success', "Catatan pengeluaran {$kode} ({$nama}) berhasil dihapus.");
    }

    /**
     * Export laporan catatan pengeluaran ke format Microsoft Excel (.xls / SpreadsheetML).
     */
    public function exportExcel(Request $request): Response
    {
        $search = $request->input('search');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if ($tanggalMulai && $tanggalSelesai && $tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [$tanggalSelesai, $tanggalMulai];
        }

        $query = Pengeluaran::with('user');

        if (! empty($tanggalMulai)) {
            $query->whereDate('tanggal_pengeluaran', '>=', $tanggalMulai);
        }

        if (! empty($tanggalSelesai)) {
            $query->whereDate('tanggal_pengeluaran', '<=', $tanggalSelesai);
        }

        if ($request->filled('search')) {
            $keyword = trim((string) $search);
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pengeluaran', 'like', "%{$keyword}%")
                    ->orWhere('nama_pengeluaran', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        $statsQuery = clone $query;
        $totalCatatan = (clone $statsQuery)->count();
        $totalPengeluaran = (float) (clone $statsQuery)->sum('nominal');
        $pengeluaranTerbesar = (float) ((clone $statsQuery)->max('nominal') ?? 0);

        $pengeluarans = $query
            ->orderBy('tanggal_pengeluaran', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if (! empty($tanggalMulai) || ! empty($tanggalSelesai)) {
            $startFmt = ! empty($tanggalMulai) ? Carbon::parse($tanggalMulai)->format('d M Y') : 'Awal';
            $endFmt = ! empty($tanggalSelesai) ? Carbon::parse($tanggalSelesai)->format('d M Y') : 'Sekarang';
            $periodeLabel = "{$startFmt} s/d {$endFmt}";
        } else {
            $periodeLabel = 'Semua Periode';
        }

        $bulanStr = now()->format('m');
        $tahun = now()->year;
        $filename = 'laporan-pengeluaran-gedambaan-'.now()->format('Ymd').'.xls';

        $xml = view('admin.pengeluaran.excel', compact(
            'pengeluarans',
            'bulanStr',
            'tahun',
            'periodeLabel',
            'totalCatatan',
            'totalPengeluaran',
            'pengeluaranTerbesar'
        ))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
        ]);
    }

    /**
     * Export laporan catatan pengeluaran ke format CSV (; separator + UTF-8 BOM).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $search = $request->input('search');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if ($tanggalMulai && $tanggalSelesai && $tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [$tanggalSelesai, $tanggalMulai];
        }

        $query = Pengeluaran::with('user');

        if (! empty($tanggalMulai)) {
            $query->whereDate('tanggal_pengeluaran', '>=', $tanggalMulai);
        }

        if (! empty($tanggalSelesai)) {
            $query->whereDate('tanggal_pengeluaran', '<=', $tanggalSelesai);
        }

        if ($request->filled('search')) {
            $keyword = trim((string) $search);
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pengeluaran', 'like', "%{$keyword}%")
                    ->orWhere('nama_pengeluaran', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        if (! empty($tanggalMulai) || ! empty($tanggalSelesai)) {
            $startFmt = ! empty($tanggalMulai) ? Carbon::parse($tanggalMulai)->format('d M Y') : 'Awal';
            $endFmt = ! empty($tanggalSelesai) ? Carbon::parse($tanggalSelesai)->format('d M Y') : 'Sekarang';
            $periodeLabel = "{$startFmt} s/d {$endFmt}";
        } else {
            $periodeLabel = 'Semua Periode';
        }

        $filename = 'laporan-pengeluaran-gedambaan-'.now()->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($query, $periodeLabel) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fwrite($handle, "sep=;\r\n");

            fputcsv($handle, ['LAPORAN CATATAN PENGELUARAN WAHANA GEDAMBAAN'], ';');
            fputcsv($handle, ['Periode', $periodeLabel], ';');
            fputcsv($handle, ['Waktu Download', now()->format('d/m/Y H:i:s')], ';');
            fputcsv($handle, [], ';');

            fputcsv($handle, [
                'No',
                'Kode Pengeluaran',
                'Tanggal Pengeluaran',
                'Nama Pengeluaran',
                'Keterangan',
                'Dicatat Oleh',
                'Nominal (Rp)',
            ], ';');

            $no = 1;
            $total = 0;
            $query->orderBy('tanggal_pengeluaran', 'asc')->orderBy('id', 'asc')->chunk(200, function ($items) use ($handle, &$no, &$total) {
                foreach ($items as $item) {
                    $total += (int) $item->nominal;
                    fputcsv($handle, [
                        $no++,
                        $item->kode_pengeluaran,
                        $item->tanggal_pengeluaran ? $item->tanggal_pengeluaran->format('d/m/Y') : '-',
                        $item->nama_pengeluaran,
                        $item->keterangan ?? '-',
                        $item->user ? $item->user->name : '-',
                        (int) $item->nominal,
                    ], ';');
                }
            });

            fputcsv($handle, ['', '', '', '', '', 'TOTAL PENGELUARAN', $total], ';');

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}

