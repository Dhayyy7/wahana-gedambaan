<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pemesanan Tiket</h1>
                <p class="text-sm text-slate-500 mt-0.5">Riwayat seluruh transaksi tiket wahana.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" onclick="openBluetoothModal()"
                    id="btn-bt-header"
                    class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-sm font-semibold px-4 py-2.5 rounded-2xl shadow-xs transition cursor-pointer">
                    <span id="bt-status-dot" class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                    <i class="fa-brands fa-bluetooth-b text-blue-600"></i>
                    <span id="bt-header-text">Printer Bluetooth</span>
                </button>

                <a href="{{ route('admin.tiket.create') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                    <i class="fa-solid fa-plus"></i>
                    Buat Tiket
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div id="flash-success"
            class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-500 shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif
    @if ($errors->any())
        <div id="flash-error"
            class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs">
            <i class="fa-solid fa-triangle-exclamation text-rose-500 shrink-0"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-rose-400 hover:text-rose-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    {{-- Stats --}}
    @php
        $hasDateFilter = !empty($tanggalMulai) || !empty($tanggalSelesai);
        $statsBase = \App\Models\Tiket::query();
        if (!empty($tanggalMulai)) {
            $statsBase->whereDate('tanggal_pemesanan', '>=', $tanggalMulai);
        }
        if (!empty($tanggalSelesai)) {
            $statsBase->whereDate('tanggal_pemesanan', '<=', $tanggalSelesai);
        }

        $totalTiket = (clone $statsBase)->count();
        $pending    = (clone $statsBase)->where('status', \App\Models\Tiket::STATUS_PENDING)->count();
        $selesai    = (clone $statsBase)->where('status', \App\Models\Tiket::STATUS_SELESAI)->count();
        $dibatalkan = (clone $statsBase)->where('status', \App\Models\Tiket::STATUS_BATAL)->count();

        if ($hasDateFilter) {
            $pendapatan = (clone $statsBase)
                ->where('status', '!=', \App\Models\Tiket::STATUS_BATAL)
                ->sum('total_bayar');
            $labelPendapatan = 'Pendapatan Periode Ini';
        } else {
            $pendapatan = \App\Models\Tiket::where('status', '!=', \App\Models\Tiket::STATUS_BATAL)
                ->whereMonth('tanggal_pemesanan', now()->month)
                ->whereYear('tanggal_pemesanan', now()->year)
                ->sum('total_bayar');
            $labelPendapatan = 'Pendapatan Bulan Ini';
        }
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg shrink-0">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalTiket }}</p>
                <p class="text-xs text-slate-500 font-medium">Total Tiket</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-lg shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $pending }}</p>
                <p class="text-xs text-slate-500 font-medium">Belum Digunakan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $selesai }}</p>
                <p class="text-xs text-slate-500 font-medium">Sudah Digunakan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 text-lg shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $dibatalkan }}</p>
                <p class="text-xs text-slate-500 font-medium">Dibatalkan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5 col-span-2 sm:col-span-1">
            <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 text-lg shrink-0">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-lg font-extrabold text-slate-800 leading-tight">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ $labelPendapatan }}</p>
            </div>
        </div>
    </div>

    {{-- Search, Date Range & Status Filter Bar --}}
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-100 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.tiket.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Input Search --}}
                <div class="sm:col-span-2">
                    <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-magnifying-glass mr-1 text-slate-400"></i> Cari Transaksi
                    </label>
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                        placeholder="Cari kode tiket, nama pembeli, no HP, atau nama wahana..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-xs sm:text-sm text-slate-700 placeholder-slate-400 rounded-2xl border border-slate-200/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition outline-none" />
                </div>

                {{-- Filter Dari Tanggal --}}
                <div>
                    <label for="tanggal_mulai" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fa-regular fa-calendar-check mr-1 text-blue-500"></i> Dari Tanggal
                    </label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tanggalMulai ?? '' }}"
                        class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200/80 bg-slate-50 hover:bg-slate-100/80 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 transition" />
                </div>

                {{-- Filter Sampai Tanggal --}}
                <div>
                    <label for="tanggal_selesai" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        <i class="fa-regular fa-calendar-check mr-1 text-blue-500"></i> Sampai Tanggal
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $tanggalSelesai ?? '' }}"
                        class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200/80 bg-slate-50 hover:bg-slate-100/80 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 transition" />
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-1">
                {{-- Filter Pembayaran Dropdown --}}
                <div class="sm:w-52">
                    <select name="metode_pembayaran" onchange="this.form.submit()"
                        class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200/80 bg-slate-50 hover:bg-slate-100/80 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 transition cursor-pointer">
                        <option value="all" {{ ($metodePembayaran ?? 'all') === 'all' ? 'selected' : '' }}>Semua Pembayaran</option>
                        <option value="tunai" {{ ($metodePembayaran ?? '') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ ($metodePembayaran ?? '') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="kosong" {{ ($metodePembayaran ?? '') === 'kosong' ? 'selected' : '' }}>Belum Diisi (-)</option>
                    </select>
                </div>

                {{-- Filter Status Dropdown --}}
                <div class="sm:w-52">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200/80 bg-slate-50 hover:bg-slate-100/80 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 transition cursor-pointer">
                        <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="0" {{ (string) ($status ?? '') === '0' ? 'selected' : '' }}>Belum Digunakan</option>
                        <option value="1" {{ (string) ($status ?? '') === '1' ? 'selected' : '' }}>Sudah Digunakan</option>
                        <option value="-1" {{ (string) ($status ?? '') === '-1' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                {{-- Submit & Reset Buttons --}}
                <div class="flex items-center gap-2 sm:ml-auto">
                    <button type="submit"
                        class="flex-1 sm:flex-initial py-2.5 px-6 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-sm shadow-blue-200 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-filter"></i>
                        <span>Terapkan Filter</span>
                    </button>

                    @if (!empty($search) || (!empty($status) && $status !== 'all') || (!empty($metodePembayaran) && $metodePembayaran !== 'all') || !empty($tanggalMulai) || !empty($tanggalSelesai))
                        <a href="{{ route('admin.tiket.index') }}"
                            class="p-2.5 bg-slate-100 hover:bg-slate-200 active:scale-98 text-slate-600 rounded-2xl transition"
                            title="Reset Pencarian & Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        {{-- Table Top Info --}}
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div>
                Menampilkan <strong class="text-slate-800 font-semibold">{{ $tikets->firstItem() ?? 0 }} - {{ $tikets->lastItem() ?? 0 }}</strong> dari <strong class="text-slate-800 font-semibold">{{ $tikets->total() }}</strong> total transaksi (10 per halaman)
            </div>
            @if (!empty($search) || (!empty($status) && $status !== 'all'))
                <span class="text-blue-600 font-medium bg-blue-50 px-2.5 py-0.5 rounded-full text-[11px]">
                    Filter aktif
                </span>
            @endif
        </div>

        @if ($tikets->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-slate-400">
                <i class="fa-solid fa-ticket text-5xl mb-4 opacity-30"></i>
                <p class="font-semibold text-slate-600 text-base">Tidak ada transaksi ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">
                    @if (!empty($search) || (!empty($status) && $status !== 'all'))
                        Tidak ada tiket yang cocok dengan kata kunci atau filter status yang dipilih.
                    @else
                        Belum ada tiket pemesanan yang tercatat di sistem.
                    @endif
                </p>
                @if (!empty($search) || (!empty($status) && $status !== 'all'))
                    <a href="{{ route('admin.tiket.index') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-50 text-blue-600 text-xs font-bold hover:bg-blue-100 transition">
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset Filter
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider">Kode Tiket</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider">Pembeli</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider hidden md:table-cell">Wahana</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider hidden sm:table-cell">Total Bayar</th>
                            <th class="px-5 py-3.5 text-center font-semibold text-slate-500 text-xs uppercase tracking-wider">Pembayaran</th>
                            <th class="px-5 py-3.5 text-center font-semibold text-slate-500 text-xs uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider hidden lg:table-cell">Waktu Pesan</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($tikets as $tiket)
                            <tr class="hover:bg-slate-50/50 transition {{ $tiket->status === -1 ? 'opacity-70 bg-slate-50/30' : '' }}">
                                {{-- Kode Tiket --}}
                                <td class="px-5 py-4">
                                    <span class="font-mono font-bold text-blue-600 text-xs bg-blue-50 px-2.5 py-1 rounded-lg">{{ $tiket->kode_tiket }}</span>
                                </td>
                                {{-- Pembeli --}}
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800 {{ $tiket->status === -1 ? 'line-through text-slate-500' : '' }}">{{ $tiket->nama_pembeli }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $tiket->no_hp }}</p>
                                </td>
                                {{-- Kapal --}}
                                <td class="px-5 py-4 hidden md:table-cell">
                                    <p class="font-medium text-slate-700">{{ $tiket->kapal->nama_kapal }}</p>
                                    <p class="text-xs text-slate-400">{{ $tiket->is_weekend ? '🌅 Weekend' : '📅 Hari Biasa' }}</p>
                                </td>
                                {{-- Total --}}
                                <td class="px-5 py-4 text-right font-semibold text-slate-800 hidden sm:table-cell {{ $tiket->status === -1 ? 'line-through text-slate-400' : '' }}">
                                    Rp {{ number_format($tiket->total_bayar, 0, ',', '.') }}
                                </td>
                                {{-- Metode Pembayaran --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($tiket->metode_pembayaran === 'qris')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 border border-sky-200">
                                            <i class="fa-solid fa-qrcode text-[10px]"></i> QRIS
                                        </span>
                                    @elseif ($tiket->metode_pembayaran === 'tunai')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-money-bill-wave text-[10px]"></i> Tunai
                                        </span>
                                    @else
                                        <a href="{{ route('admin.tiket.edit', $tiket) }}"
                                            class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-600 border border-dashed border-slate-200 hover:border-blue-300 transition"
                                            title="Klik untuk mengisi jenis pembayaran">
                                            <span>-</span>
                                            <i class="fa-solid fa-pen text-[9px]"></i>
                                        </a>
                                    @endif
                                </td>
                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($tiket->status === 0)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Belum Digunakan
                                        </span>
                                    @elseif ($tiket->status === -1)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Dibatalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200"
                                            title="{{ $tiket->tanggal_scan ? 'Di-scan QR: ' . $tiket->tanggal_scan->format('d/m/Y H:i') : 'Selesai Otomatis 12 Jam (Manual)' }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Sudah Digunakan
                                        </span>
                                    @endif
                                </td>
                                {{-- Waktu --}}
                                <td class="px-5 py-4 text-slate-500 text-xs hidden lg:table-cell">
                                    {{ $tiket->tanggal_pemesanan->format('d M Y, H:i') }}
                                </td>
                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.tiket.show', $tiket) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                                            title="Lihat Detail">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>

                                        <a href="{{ route('admin.tiket.edit', $tiket) }}"
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition"
                                            title="Edit Tiket / Jenis Pembayaran">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>

                                        @if ($tiket->status !== -1)
                                            <button type="button"
                                                onclick="printSingleTicketBluetooth(this)"
                                                data-tiket="{{ json_encode([
                                                    'kode_tiket' => $tiket->kode_tiket,
                                                    'waktu' => $tiket->tanggal_pemesanan->format('d/m/Y H:i'),
                                                    'kasir' => Auth::user()->name,
                                                    'nama_pembeli' => $tiket->nama_pembeli,
                                                    'no_hp' => $tiket->no_hp,
                                                    'alamat' => $tiket->alamat ?? '',
                                                    'kapal' => $tiket->kapal->nama_kapal,
                                                    'hari' => $tiket->is_weekend ? 'Weekend' : 'Hari Biasa',
                                                    'pembayaran' => $tiket->metode_pembayaran_label !== '-' ? $tiket->metode_pembayaran_label : null,
                                                    'tarif' => number_format($tiket->tarif, 0, ',', '.'),
                                                    'diskon' => (float) $tiket->diskon > 0 ? number_format($tiket->diskon, 0, ',', '.') : null,
                                                    'total' => number_format($tiket->total_bayar, 0, ',', '.'),
                                                    'qr_code' => $tiket->qr_code,
                                                ]) }}"
                                                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer"
                                                title="Cetak Langsung via Bluetooth">
                                                <i class="fa-brands fa-bluetooth-b text-sm"></i>
                                            </button>

                                            <a href="{{ route('admin.tiket.print', $tiket) }}" target="_blank"
                                                class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition"
                                                title="Cetak Struk Browser (58mm)">
                                                <i class="fa-solid fa-print text-sm"></i>
                                            </a>
                                        @endif

                                        {{-- Tombol Batalkan Pesanan (Bisa digunakan kasir jika salah input, status menjadi -1) --}}
                                        @if ($tiket->status === 0)
                                            <form method="POST" action="{{ route('admin.tiket.cancel', $tiket) }}"
                                                onsubmit="return confirmAction(event, {
                                                    title: 'Batalkan Pesanan Tiket?',
                                                    text: 'Apakah Anda yakin ingin membatalkan pesanan tiket {{ $tiket->kode_tiket }}? Status tiket akan diubah menjadi Dibatalkan.',
                                                    type: 'warning',
                                                    confirmText: 'Ya, Batalkan',
                                                    cancelText: 'Kembali'
                                                })">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition"
                                                    title="Batalkan Pesanan (Salah Input)">
                                                    <i class="fa-solid fa-ban text-sm"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Hapus: HANYA untuk Superadmin agar Kasir tidak bisa menghapus transaksi untuk menghindari transaksi fiktif --}}
                                        @if (auth()->user()->isSuperadmin() && $tiket->status === 0)
                                            <form method="POST" action="{{ route('admin.tiket.destroy', $tiket) }}"
                                                onsubmit="return confirmAction(event, {
                                                    title: 'Hapus Tiket Permanen?',
                                                    text: 'Hapus permanen tiket {{ $tiket->kode_tiket }} dari sistem? Data ini tidak dapat dikembalikan.',
                                                    type: 'danger',
                                                    confirmText: 'Ya, Hapus',
                                                    cancelText: 'Batal'
                                                })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition"
                                                    title="Hapus Tiket Permanen (Superadmin Only)">
                                                    <i class="fa-solid fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($tikets->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $tikets->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- Modal Printer Bluetooth & Script --}}
    @include('admin.tiket.partials.bluetooth-modal')
    <script src="{{ asset('js/thermal-bluetooth.js') }}?v={{ @filemtime(public_path('js/thermal-bluetooth.js')) ?: 2 }}"></script>
</x-app-layout>
