<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTiketRequest;
use App\Http\Requests\Admin\UpdateTiketRequest;
use App\Models\Kapal;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TiketController extends Controller
{
    /**
     * Daftar semua tiket (riwayat transaksi) dengan pagination 10 per halaman, pencarian, dan filter status.
     */
    public function index(Request $request): View
    {
        Tiket::syncExpiredTickets();

        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $metodePembayaran = $request->input('metode_pembayaran', 'all');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $query = Tiket::with('kapal');

        if ($request->filled('search')) {
            $keyword = trim((string) $search);
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_tiket', 'like', "%{$keyword}%")
                    ->orWhere('nama_pembeli', 'like', "%{$keyword}%")
                    ->orWhere('no_hp', 'like', "%{$keyword}%")
                    ->orWhere('alamat', 'like', "%{$keyword}%")
                    ->orWhereHas('kapal', function ($kq) use ($keyword) {
                        $kq->where('nama_kapal', 'like', "%{$keyword}%")
                            ->orWhere('kode_kapal', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pemesanan', '>=', $tanggalMulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pemesanan', '<=', $tanggalSelesai);
        }

        if ($status !== 'all' && $status !== '' && $status !== null) {
            $query->where('status', (int) $status);
        }

        if ($metodePembayaran === 'kosong') {
            $query->whereNull('metode_pembayaran');
        } elseif (in_array($metodePembayaran, [Tiket::METODE_TUNAI, Tiket::METODE_QRIS], true)) {
            $query->where('metode_pembayaran', $metodePembayaran);
        }

        $tikets = $query
            ->latest('tanggal_pemesanan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tiket.index', compact(
            'tikets',
            'search',
            'status',
            'metodePembayaran',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    /**
     * Form kasir untuk membuat tiket baru.
     */
    public function create(): View
    {
        Tiket::ensureSchemaUpdated();

        // Hanya tampilkan kapal yang statusnya Tersedia
        $kapals = Kapal::where('status', Kapal::STATUS_TERSEDIA)->orderBy('nama_kapal')->get();

        return view('admin.tiket.create', compact('kapals'));
    }

    /**
     * Simpan tiket baru (mendukung pembuatan banyak tiket sekaligus dengan data pembeli yang sama).
     */
    public function store(StoreTiketRequest $request): RedirectResponse
    {
        Tiket::ensureSchemaUpdated();

        $kapal = Kapal::findOrFail($request->kapal_id);

        // Validasi tambahan: wahana harus dalam status tersedia
        if (! $kapal->isTersedia()) {
            return back()
                ->withInput()
                ->withErrors(['kapal_id' => 'Wahana yang dipilih sedang tidak tersedia ('.$kapal->status_label.').']);
        }

        $jumlahTiket = max(1, min(50, (int) $request->input('jumlah_tiket', 1)));
        $isWeekend = now()->isWeekend();
        $tarif = $isWeekend ? (float) $kapal->tarif_weekend : (float) $kapal->tarif_biasa;
        $diskon = (float) $kapal->diskon;
        $totalBayar = max(0, $tarif - $diskon);
        $now = now();

        $createdTikets = DB::transaction(function () use ($request, $kapal, $jumlahTiket, $tarif, $diskon, $totalBayar, $isWeekend, $now) {
            $items = collect();

            for ($i = 0; $i < $jumlahTiket; $i++) {
                $items->push(Tiket::create([
                    'kode_tiket' => Tiket::generateKodeTiket(),
                    'kapal_id' => $kapal->id,
                    'nama_pembeli' => $request->nama_pembeli,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'tarif' => $tarif,
                    'diskon' => $diskon,
                    'total_bayar' => $totalBayar,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'is_weekend' => $isWeekend,
                    'qr_code' => Str::uuid()->toString(),
                    'status' => Tiket::STATUS_PENDING,
                    'tanggal_pemesanan' => $now,
                ]));
            }

            return $items;
        });

        /** @var Tiket $firstTiket */
        $firstTiket = $createdTikets->first();

        if ($jumlahTiket > 1) {
            /** @var Tiket $lastTiket */
            $lastTiket = $createdTikets->last();
            $batchIds = $createdTikets->pluck('id')->implode(',');

            return redirect()
                ->route('admin.tiket.show', ['tiket' => $firstTiket->id, 'batch' => $batchIds])
                ->with('success', "Berhasil membuat {$jumlahTiket} tiket sekaligus ({$firstTiket->kode_tiket} s/d {$lastTiket->kode_tiket})! Silakan cetak tiket.");
        }

        return redirect()
            ->route('admin.tiket.show', $firstTiket)
            ->with('success', "Tiket {$firstTiket->kode_tiket} berhasil dibuat! Silakan cetak tiket.");
    }

    /**
     * Detail tiket + tampilan untuk print (mendukung tampilan banyak tiket sekaligus via parameter ?batch=...).
     */
    public function show(Request $request, Tiket $tiket): View
    {
        if ($tiket->isPending() && $tiket->isExpired()) {
            $tiket->update(['status' => Tiket::STATUS_SELESAI]);
        }

        $tiket->load('kapal');

        $batchTikets = collect([$tiket]);
        if ($request->filled('batch')) {
            $ids = collect(explode(',', (string) $request->query('batch')))
                ->map(fn ($id) => (int) trim($id))
                ->filter()
                ->unique()
                ->take(50);

            if ($ids->isNotEmpty()) {
                $loaded = Tiket::with('kapal')
                    ->whereIn('id', $ids)
                    ->orderBy('id')
                    ->get();

                if ($loaded->isNotEmpty()) {
                    $batchTikets = $loaded;
                }
            }
        }

        return view('admin.tiket.show', compact('tiket', 'batchTikets'));
    }

    /**
     * Halaman khusus cetak struk termal 58mm (standalone, mendukung cetak banyak tiket sekaligus via ?batch=...).
     */
    public function print(Request $request, Tiket $tiket): View
    {
        if ($tiket->isPending() && $tiket->isExpired()) {
            $tiket->update(['status' => Tiket::STATUS_SELESAI]);
        }

        $tiket->load('kapal');

        $batchTikets = collect([$tiket]);
        if ($request->filled('batch')) {
            $ids = collect(explode(',', (string) $request->query('batch')))
                ->map(fn ($id) => (int) trim($id))
                ->filter()
                ->unique()
                ->take(50);

            if ($ids->isNotEmpty()) {
                $loaded = Tiket::with('kapal')
                    ->whereIn('id', $ids)
                    ->orderBy('id')
                    ->get();

                if ($loaded->isNotEmpty()) {
                    $batchTikets = $loaded;
                }
            }
        }

        return view('admin.tiket.print', compact('tiket', 'batchTikets'));
    }

    /**
     * Form edit tiket (untuk memperbarui jenis pembayaran QRIS/Tunai dan data pengunjung).
     */
    public function edit(Tiket $tiket): View
    {
        Tiket::ensureSchemaUpdated();

        $tiket->load('kapal');

        $relatedCount = 0;
        if ($tiket->tanggal_pemesanan) {
            $relatedCount = Tiket::where('id', '!=', $tiket->id)
                ->where('nama_pembeli', $tiket->nama_pembeli)
                ->whereDate('tanggal_pemesanan', $tiket->tanggal_pemesanan->toDateString())
                ->count();
        }

        return view('admin.tiket.edit', compact('tiket', 'relatedCount'));
    }

    /**
     * Simpan perubahan data tiket (terutama metode pembayaran QRIS/Tunai dan data pengunjung).
     */
    public function update(UpdateTiketRequest $request, Tiket $tiket): RedirectResponse
    {
        $oldNamaPembeli = $tiket->nama_pembeli;
        $metodePembayaran = $request->filled('metode_pembayaran') ? $request->metode_pembayaran : null;

        $tiket->update([
            'metode_pembayaran' => $metodePembayaran,
            'nama_pembeli' => $request->nama_pembeli,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        $updatedRelated = 0;
        if ($request->boolean('apply_same_buyer') && $tiket->tanggal_pemesanan && $metodePembayaran !== null) {
            $updatedRelated = Tiket::where('id', '!=', $tiket->id)
                ->where('nama_pembeli', $oldNamaPembeli)
                ->whereDate('tanggal_pemesanan', $tiket->tanggal_pemesanan->toDateString())
                ->update([
                    'metode_pembayaran' => $metodePembayaran,
                ]);
        }

        $pesan = "Data tiket {$tiket->kode_tiket} berhasil diperbarui.";
        if ($updatedRelated > 0) {
            $labelMetode = strtoupper($metodePembayaran);
            $pesan .= " Jenis pembayaran ({$labelMetode}) juga diterapkan ke {$updatedRelated} tiket lainnya.";
        }

        if ($request->input('redirect_to') === 'laporan') {
            return redirect()
                ->route('admin.laporan.index')
                ->with('success', $pesan);
        }

        return redirect()
            ->route('admin.tiket.index')
            ->with('success', $pesan);
    }

    /**
     * Batalkan pesanan tiket (kasir salah input).
     * Status diubah menjadi -1 (STATUS_BATAL) agar riwayat tetap tersimpan untuk audit
     * dan mencegah manipulasi/penghapusan transaksi fiktif di kasir.
     */
    public function cancel(Tiket $tiket): RedirectResponse
    {
        if ($tiket->isPending() && $tiket->isExpired()) {
            $tiket->update(['status' => Tiket::STATUS_SELESAI]);
        }

        if ($tiket->isSudahDigunakan()) {
            $pesan = $tiket->isAutoSelesai()
                ? 'Tiket telah melewati masa berlaku 12 jam (otomatis selesai) dan tidak dapat dibatalkan.'
                : 'Tiket yang sudah digunakan tidak dapat dibatalkan.';

            return back()->withErrors(['error' => $pesan]);
        }

        if ($tiket->isDibatalkan()) {
            return back()->withErrors(['error' => "Tiket {$tiket->kode_tiket} sudah berstatus dibatalkan sebelumnya."]);
        }

        $tiket->update([
            'status' => Tiket::STATUS_BATAL,
        ]);

        return redirect()
            ->back()
            ->with('success', "Pesanan tiket {$tiket->kode_tiket} berhasil dibatalkan.");
    }

    /**
     * Hapus tiket (hanya Superadmin yang berhak menghapus data transaksi tiket, kasir tidak diizinkan untuk menghindari fake transaksi).
     */
    public function destroy(Tiket $tiket): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->isSuperadmin()) {
            return back()->withErrors(['error' => 'Kasir tidak diizinkan menghapus transaksi. Gunakan aksi Batalkan Pesanan jika terjadi salah input.']);
        }

        if ($tiket->isPending() && $tiket->isExpired()) {
            $tiket->update(['status' => Tiket::STATUS_SELESAI]);
        }

        if ($tiket->isSudahDigunakan()) {
            return back()->withErrors(['error' => 'Tiket yang sudah digunakan tidak dapat dihapus.']);
        }

        $kode = $tiket->kode_tiket;
        $tiket->delete();

        return redirect()
            ->route('admin.tiket.index')
            ->with('success', "Tiket {$kode} berhasil dihapus.");
    }
}
