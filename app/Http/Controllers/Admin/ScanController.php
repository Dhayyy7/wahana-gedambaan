<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\Tiket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ScanController extends Controller
{
    /**
     * Tampilan halaman scanner QR Code tiket untuk operator.
     */
    public function index(): View
    {
        // Ambil daftar kapal yang sedang berlayar
        $kapalBerlayar = Kapal::where('status', Kapal::STATUS_SEDANG_BERLAYAR)
            ->orderBy('waktu_mulai_trip', 'desc')
            ->get();

        // Riwayat tiket yang baru di-scan hari ini
        $scanHariIni = Tiket::with('kapal')
            ->whereNotNull('tanggal_scan')
            ->whereDate('tanggal_scan', today())
            ->latest('tanggal_scan')
            ->take(10)
            ->get();

        $totalScanHariIni = Tiket::whereDate('tanggal_scan', today())->count();

        return view('admin.scan.index', compact('kapalBerlayar', 'scanHariIni', 'totalScanHariIni'));
    }

    /**
     * Proses validasi dan update status tiket + kapal dari scan QR Code.
     */
    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'qr_code' => ['required', 'string'],
        ], [
            'qr_code.required' => 'Kode tiket atau QR Code tidak boleh kosong.',
        ]);

        $payload = trim($request->input('qr_code'));

        // Cari tiket berdasarkan UUID qr_code atau kode_tiket (misal: TKT-20261018-0001)
        $tiket = Tiket::with('kapal')
            ->where('qr_code', $payload)
            ->orWhere('kode_tiket', $payload)
            ->first();

        // 1. Validasi keberadaan tiket
        if (! $tiket) {
            return response()->json([
                'success' => false,
                'code' => 'NOT_FOUND',
                'message' => 'Tiket tidak ditemukan! Pastikan QR Code atau Kode Tiket valid.',
            ], 404);
        }

        // 2. Validasi 1 Tiket = 1x Naik (Tiket tidak bisa dipakai 2x)
        if ($tiket->isSudahDigunakan()) {
            return response()->json([
                'success' => false,
                'code' => 'ALREADY_USED',
                'message' => 'Tiket sudah pernah digunakan pada '.($tiket->tanggal_scan ? $tiket->tanggal_scan->format('d/m/Y H:i') : '-').' WIB. 1 tiket hanya berlaku untuk 1 kali perjalanan!',
                'tiket' => [
                    'kode_tiket' => $tiket->kode_tiket,
                    'nama_pembeli' => $tiket->nama_pembeli,
                    'nama_kapal' => $tiket->kapal ? $tiket->kapal->nama_kapal : '-',
                    'tanggal_scan' => $tiket->tanggal_scan ? $tiket->tanggal_scan->format('d M Y, H:i') : '-',
                ],
            ], 422);
        }

        // 3. Validasi Kapal
        $kapal = $tiket->kapal;
        if (! $kapal) {
            return response()->json([
                'success' => false,
                'code' => 'KAPAL_NOT_FOUND',
                'message' => 'Data kapal untuk tiket ini tidak ditemukan dalam database.',
            ], 422);
        }

        // 4. Validasi Kapal Sedang Berlayar (Sistem tidak bisa scan unit kapal yang sama saat masih berlayar)
        if ($kapal->isSedangBerlayar()) {
            $waktuMulai = $kapal->waktu_mulai_trip ? $kapal->waktu_mulai_trip->format('H:i') : '-';
            $estimasiSelesai = $kapal->waktu_mulai_trip ? $kapal->waktu_mulai_trip->copy()->addMinutes(30)->format('H:i') : '-';

            return response()->json([
                'success' => false,
                'code' => 'KAPAL_BUSY',
                'message' => "Kapal {$kapal->nama_kapal} ({$kapal->kode_kapal}) saat ini sedang dipakai berlayar sejak pukul {$waktuMulai} WIB (estimasi trip selesai {$estimasiSelesai} WIB). Harap tunggu trip selesai sebelum scan tiket berikutnya untuk kapal ini!",
                'kapal' => [
                    'id' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'kode_kapal' => $kapal->kode_kapal,
                    'waktu_mulai_trip' => $waktuMulai,
                    'estimasi_selesai' => $estimasiSelesai,
                ],
            ], 422);
        }

        // 5. Validasi Kapal Maintenance
        if ($kapal->status === Kapal::STATUS_MAINTENANCE) {
            return response()->json([
                'success' => false,
                'code' => 'KAPAL_MAINTENANCE',
                'message' => "Kapal {$kapal->nama_kapal} sedang dalam masa perbaikan (Maintenance) dan belum siap berlayar.",
            ], 422);
        }

        // 6. Eksekusi Transaksi: Update Tiket -> Digunakan & Kapal -> Sedang Berlayar
        DB::transaction(function () use ($tiket, $kapal) {
            $now = now();

            $tiket->update([
                'status' => Tiket::STATUS_SELESAI,
                'tanggal_scan' => $now,
            ]);

            $kapal->update([
                'status' => Kapal::STATUS_SEDANG_BERLAYAR,
                'waktu_mulai_trip' => $now,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Tiket Berhasil Diverifikasi! Selamat Menikmati Perjalanan.',
            'data' => [
                'kode_tiket' => $tiket->kode_tiket,
                'nama_pembeli' => $tiket->nama_pembeli,
                'no_hp' => $tiket->no_hp,
                'kapal' => [
                    'id' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'kode_kapal' => $kapal->kode_kapal,
                ],
                'waktu_scan' => now()->format('d M Y, H:i:s'),
                'estimasi_kembali' => now()->addMinutes(30)->format('H:i'),
            ],
        ]);
    }

    /**
     * Selesaikan trip kapal secara manual oleh operator (jika kapal kembali sebelum 30 menit).
     */
    public function selesaiTrip(Kapal $kapal): JsonResponse
    {
        if (! $kapal->isSedangBerlayar()) {
            return response()->json([
                'success' => false,
                'message' => "Kapal {$kapal->nama_kapal} tidak sedang dalam status berlayar.",
            ], 422);
        }

        $kapal->update([
            'status' => Kapal::STATUS_TERSEDIA,
            'waktu_mulai_trip' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Trip kapal {$kapal->nama_kapal} ({$kapal->kode_kapal}) telah diselesaikan. Kapal kini berstatus Tersedia.",
        ]);
    }
}
