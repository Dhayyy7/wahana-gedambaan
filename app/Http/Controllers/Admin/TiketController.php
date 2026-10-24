<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTiketRequest;
use App\Models\Kapal;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TiketController extends Controller
{
    /**
     * Daftar semua tiket (riwayat transaksi).
     */
    public function index(): View
    {
        $tikets = Tiket::with('kapal')
            ->latest('tanggal_pemesanan')
            ->paginate(15);

        return view('admin.tiket.index', compact('tikets'));
    }

    /**
     * Form kasir untuk membuat tiket baru.
     */
    public function create(): View
    {
        // Hanya tampilkan kapal yang statusnya Tersedia
        $kapals = Kapal::where('status', Kapal::STATUS_TERSEDIA)->orderBy('nama_kapal')->get();

        return view('admin.tiket.create', compact('kapals'));
    }

    /**
     * Simpan tiket baru — hitung tarif otomatis dari data kapal.
     */
    public function store(StoreTiketRequest $request): RedirectResponse
    {
        $kapal = Kapal::findOrFail($request->kapal_id);

        // Validasi tambahan: kapal harus dalam status tersedia
        if (! $kapal->isTersedia()) {
            return back()
                ->withInput()
                ->withErrors(['kapal_id' => 'Kapal yang dipilih sedang tidak tersedia ('.$kapal->status_label.').']);
        }

        $isWeekend = now()->isWeekend();
        $tarif = $isWeekend ? (float) $kapal->tarif_weekend : (float) $kapal->tarif_biasa;
        $diskon = (float) $kapal->diskon;
        $totalBayar = max(0, $tarif - $diskon);

        $tiket = Tiket::create([
            'kode_tiket' => Tiket::generateKodeTiket(),
            'kapal_id' => $kapal->id,
            'nama_pembeli' => $request->nama_pembeli,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tarif' => $tarif,
            'diskon' => $diskon,
            'total_bayar' => $totalBayar,
            'is_weekend' => $isWeekend,
            'qr_code' => Str::uuid()->toString(),
            'status' => Tiket::STATUS_PENDING,
            'tanggal_pemesanan' => now(),
        ]);

        return redirect()
            ->route('admin.tiket.show', $tiket)
            ->with('success', "Tiket {$tiket->kode_tiket} berhasil dibuat! Silakan cetak tiket.");
    }

    /**
     * Detail tiket + tampilan untuk print.
     */
    public function show(Tiket $tiket): View
    {
        $tiket->load('kapal');

        return view('admin.tiket.show', compact('tiket'));
    }

    /**
     * Halaman khusus cetak struk termal 58mm (standalone).
     */
    public function print(Tiket $tiket): View
    {
        $tiket->load('kapal');

        return view('admin.tiket.print', compact('tiket'));
    }

    /**
     * Daftar tiket tidak diperbarui manual oleh admin.
     * Method ini tidak dipakai (update tiket dilakukan oleh operator scan).
     */
    public function edit(Tiket $tiket): View
    {
        return view('admin.tiket.show', compact('tiket'));
    }

    public function update(): RedirectResponse
    {
        return redirect()->route('admin.tiket.index');
    }

    /**
     * Batalkan pesanan tiket (kasir salah input).
     * Status diubah menjadi -1 (STATUS_BATAL) agar riwayat tetap tersimpan untuk audit
     * dan mencegah manipulasi/penghapusan transaksi fiktif di kasir.
     */
    public function cancel(Tiket $tiket): RedirectResponse
    {
        if ($tiket->isSudahDigunakan()) {
            return back()->withErrors(['error' => 'Tiket yang sudah digunakan tidak dapat dibatalkan.']);
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
