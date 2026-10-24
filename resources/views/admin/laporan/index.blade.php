<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Laporan Transaksi</h1>
                <p class="text-sm text-slate-500 mt-0.5">Rekapitulasi data transaksi pemesanan dan pendapatan tiket wahana per bulan.</p>
            </div>
            <div class="flex items-center gap-2.5">
                {{-- Tombol Export Excel Terformat --}}
                <a href="{{ route('admin.laporan.export-excel', request()->all()) }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white text-sm font-bold px-4 py-2.5 rounded-2xl shadow-sm shadow-emerald-200 transition"
                    title="Download Laporan Excel (Format Tabel Rapi, Warna, Border, & Formula)">
                    <i class="fa-solid fa-file-excel text-base"></i>
                    <span>Export Excel</span>
                </a>

                {{-- Tombol Export CSV (Separator ;) --}}
                <a href="{{ route('admin.laporan.export-csv', request()->all()) }}"
                    class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 active:scale-98 text-slate-700 text-sm font-semibold px-3.5 py-2.5 rounded-2xl border border-slate-200/80 transition"
                    title="Download File CSV Mentah">
                    <i class="fa-solid fa-file-csv text-base text-slate-500"></i>
                    <span>CSV</span>
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp

    {{-- Filter Form --}}
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end">
            {{-- Filter Bulan --}}
            <div>
                <label for="bulan" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    <i class="fa-regular fa-calendar mr-1 text-slate-400"></i> Bulan
                </label>
                <select name="bulan" id="bulan"
                    class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 transition">
                    @foreach ($namaBulan as $num => $nama)
                        <option value="{{ $num }}" {{ (int) $bulan === $num ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tahun --}}
            <div>
                <label for="tahun" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    <i class="fa-regular fa-calendar-days mr-1 text-slate-400"></i> Tahun
                </label>
                <select name="tahun" id="tahun"
                    class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 transition">
                    @foreach ($daftarTahun as $th)
                        <option value="{{ $th }}" {{ (int) $tahun === $th ? 'selected' : '' }}>
                            {{ $th }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kapal --}}
            <div>
                <label for="kapal_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    <i class="fa-solid fa-ship mr-1 text-slate-400"></i> Speedboat
                </label>
                <select name="kapal_id" id="kapal_id"
                    class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 transition">
                    <option value="all">Semua Kapal</option>
                    @foreach ($kapals as $kapal)
                        <option value="{{ $kapal->id }}" {{ (string) $kapalId === (string) $kapal->id ? 'selected' : '' }}>
                            {{ $kapal->nama_kapal }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status --}}
            <div>
                <label for="status" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    <i class="fa-solid fa-filter mr-1 text-slate-400"></i> Status Tiket
                </label>
                <select name="status" id="status"
                    class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 transition">
                    <option value="all" {{ (string) $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="1" {{ (string) $status === '1' ? 'selected' : '' }}>Sudah Digunakan</option>
                    <option value="0" {{ (string) $status === '0' ? 'selected' : '' }}>Belum Digunakan</option>
                    <option value="-1" {{ (string) $status === '-1' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- Action Buttons Filter --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-sm shadow-blue-200 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('admin.laporan.index') }}"
                    class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl transition"
                    title="Reset Filter ke Bulan Ini">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Stats Cards Ringkasan Periode Terpilih --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 mb-6">
        {{-- Total Pendapatan Bersih --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex flex-col justify-between col-span-2 sm:col-span-1 lg:col-span-2 bg-gradient-to-br from-white to-blue-50/40">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pendapatan Bersih</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Periode {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}
                </p>
            </div>
        </div>

        {{-- Total Transaksi --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tiket</span>
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-ticket"></i>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl sm:text-2xl font-black text-slate-800">{{ $totalTransaksi }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Semua entri</p>
            </div>
        </div>

        {{-- Tiket Selesai --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Digunakan</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl sm:text-2xl font-black text-emerald-600">{{ $tiketSelesai }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Sudah naik kapal</p>
            </div>
        </div>

        {{-- Tiket Belum Digunakan --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dipakai</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl sm:text-2xl font-black text-amber-600">{{ $tiketPending }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Belum verifikasi</p>
            </div>
        </div>

        {{-- Tiket Dibatalkan --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dibatalkan</span>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-ban"></i>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl sm:text-2xl font-black text-rose-600">{{ $tiketBatal }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Void / salah input</p>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Transaksi --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-4.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Rincian Transaksi</h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Menampilkan transaksi bulan <strong class="text-slate-700">{{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}</strong>
                    @if ($kapalId !== 'all')
                        • Kapal: <strong class="text-slate-700">{{ $kapals->firstWhere('id', $kapalId)?->nama_kapal }}</strong>
                    @endif
                    @if ($status !== 'all')
                        • Status: <strong class="text-slate-700">{{ $status == 1 ? 'Sudah Digunakan' : ($status == 0 ? 'Belum Digunakan' : 'Dibatalkan') }}</strong>
                    @endif
                </p>
            </div>
            <div class="text-xs text-slate-500 font-medium">
                Ditemukan <span class="font-bold text-blue-600">{{ $tikets->total() }}</span> transaksi
            </div>
        </div>

        @if ($tikets->isEmpty())
            <div class="py-20 text-center text-slate-400">
                <div class="w-16 h-16 rounded-3xl bg-slate-50 flex items-center justify-center mx-auto mb-3 text-2xl text-slate-400 border border-slate-100">
                    <i class="fa-regular fa-folder-open"></i>
                </div>
                <p class="text-sm font-bold text-slate-600">Tidak ada data transaksi</p>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    Tidak ditemukan catatan pemesanan tiket pada periode dan filter yang Anda pilih.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">No</th>
                            <th class="px-5 py-3.5">Kode Tiket</th>
                            <th class="px-5 py-3.5">Waktu Pemesanan</th>
                            <th class="px-5 py-3.5">Pembeli</th>
                            <th class="px-5 py-3.5">Speedboat</th>
                            <th class="px-5 py-3.5">Tarif</th>
                            <th class="px-5 py-3.5">Diskon</th>
                            <th class="px-5 py-3.5 text-right">Total Bayar</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($tikets as $index => $tiket)
                            <tr class="hover:bg-slate-50/60 transition {{ $tiket->status === -1 ? 'bg-slate-50/30 opacity-75' : '' }}">
                                <td class="px-5 py-3.5 text-slate-400 font-mono text-xs">
                                    {{ $tikets->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono font-bold text-blue-600 bg-blue-50/80 px-2.5 py-1 rounded-xl text-xs border border-blue-100">
                                        {{ $tiket->kode_tiket }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-xs">
                                    {{ $tiket->tanggal_pemesanan ? $tiket->tanggal_pemesanan->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-bold text-slate-800 {{ $tiket->status === -1 ? 'line-through text-slate-500' : '' }}">
                                        {{ $tiket->nama_pembeli }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $tiket->no_hp }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-700">{{ $tiket->kapal ? $tiket->kapal->nama_kapal : '-' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $tiket->is_weekend ? '🌅 Weekend' : '📅 Hari Biasa' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    Rp {{ number_format($tiket->tarif, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">
                                    @if ($tiket->diskon > 0)
                                        <span class="text-rose-600 font-semibold">- Rp {{ number_format($tiket->diskon, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-extrabold {{ $tiket->status === -1 ? 'line-through text-slate-400' : 'text-slate-800' }}">
                                    Rp {{ number_format($tiket->total_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($tiket->status === 1)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Sudah Digunakan
                                        </span>
                                    @elseif ($tiket->status === 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Belum Digunakan
                                        </span>
                                    @elseif ($tiket->status === -1)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="{{ route('admin.tiket.show', $tiket) }}"
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition inline-block"
                                        title="Lihat Detail Tiket">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($tikets->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $tikets->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
