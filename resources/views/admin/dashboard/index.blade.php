<x-app-layout>
    <div class="space-y-6">

        <!-- ================= 1. WELCOME HERO BANNER ================= -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0058b8] via-[#0275d8] to-[#0ea5e9] text-white shadow-xl shadow-blue-900/10 min-h-[200px] flex items-center">
            <!-- Background Image Speedboat dengan Overlay Transparan -->
            <div class="absolute inset-0 bg-cover bg-right bg-no-repeat opacity-30 mix-blend-overlay pointer-events-none" style="background-image: url('{{ asset('images/speedboat-banner.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#004b9e] via-[#0267c1]/90 to-transparent"></div>

            <div class="relative z-10 px-6 py-8 sm:px-10 max-w-2xl">
                <span class="text-xs sm:text-sm font-medium text-sky-200 tracking-wide flex items-center gap-2">
                    <i class="fa-regular fa-sun text-amber-300"></i>
                    Selamat datang kembali,
                </span>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </h1>
                    <span class="text-2xl sm:text-3xl animate-bounce">🚤</span>
                </div>
                <p class="mt-2 text-xs sm:text-sm text-sky-100/90 leading-relaxed max-w-lg font-normal">
                    Kelola pemesanan, jadwal kapal, verifikasi tiket penumpang, dan pantau pendapatan Wahana Gedambaan secara real-time.
                </p>
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.tiket.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-blue-600 font-bold text-xs sm:text-sm shadow-md hover:bg-blue-50 active:scale-95 transition-all duration-200">
                        <i class="fa-regular fa-calendar-check text-blue-600"></i>
                        <span>Lihat Pemesanan Hari Ini</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ route('admin.tiket.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-sky-500/80 hover:bg-sky-500 text-white font-bold text-xs sm:text-sm shadow-md border border-white/20 active:scale-95 transition-all duration-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Buat Tiket Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= 2. FOUR STAT CARDS ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            <!-- Card 1: Total Pemesanan -->
            <div class="bg-white rounded-3xl p-5 shadow-xs border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Total Pemesanan</span>
                            <span class="text-2xl font-extrabold text-slate-800 mt-0.5 block">{{ $totalPemesanan }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2.5 border-t border-slate-50 text-xs">
                    <span class="font-bold flex items-center gap-1.5 {{ $persenPemesanan >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        <i class="fa-solid {{ $persenPemesanan >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                        <span>{{ abs($persenPemesanan) }}%</span>
                        <span class="text-slate-400 font-normal">dari minggu lalu</span>
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium font-mono">
                        {{ $pemesananMingguIni }} minggu ini
                    </span>
                </div>
            </div>

            <!-- Card 2: Pelanggan Aktif -->
            <div class="bg-white rounded-3xl p-5 shadow-xs border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Pengunjung Terdaftar</span>
                            <span class="text-2xl font-extrabold text-slate-800 mt-0.5 block">{{ $pelangganAktif }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2.5 border-t border-slate-50 text-xs text-slate-400">
                    <span class="text-slate-500 font-medium flex items-center gap-1.5">
                        <i class="fa-regular fa-circle-check text-emerald-500"></i>
                        Pelanggan Unik
                    </span>
                    <a href="{{ route('admin.tiket.index') }}" class="text-sky-600 font-bold hover:underline text-[11px]">
                        Rincian &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 3: Kapal Tersedia -->
            <div class="bg-white rounded-3xl p-5 shadow-xs border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-ship"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Kapal Siap Operasi</span>
                            <span class="text-2xl font-extrabold text-slate-800 mt-0.5 block">{{ $kapalTersedia }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2.5 border-t border-slate-50 text-xs">
                    <span class="text-slate-500 font-medium">
                        Dari {{ $totalKapal }} total armada
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                        {{ $kapalBerlayar }} Berlayar
                    </span>
                </div>
            </div>

            <!-- Card 4: Pendapatan Bulan Ini -->
            <div class="bg-white rounded-3xl p-5 shadow-xs border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-black shrink-0">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Pendapatan Bulan Ini</span>
                            <span class="text-lg sm:text-xl font-extrabold text-slate-800 mt-0.5 block">
                                Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.laporan.index') }}" class="text-[11px] font-bold text-sky-600 hover:text-sky-700 hover:underline flex items-center gap-0.5">
                        Laporan &rarr;
                    </a>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2.5 border-t border-slate-50 text-xs">
                    <span class="font-bold flex items-center gap-1.5 {{ $persenPendapatan >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        <i class="fa-solid {{ $persenPendapatan >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                        <span>{{ abs($persenPendapatan) }}%</span>
                        <span class="text-slate-400 font-normal">dari bulan lalu</span>
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">
                        {{ now()->translatedFormat('F Y') }}
                    </span>
                </div>
            </div>

        </div>

        <!-- ================= 3. CHARTS, STATUS & RECENT BOOKINGS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Left & Center: Chart & Status (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Tren Pemesanan 7 Hari Terakhir Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xs border border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-chart-column"></i>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-800">
                                    Tren Pemesanan 7 Hari Terakhir
                                </h2>
                                <p class="text-xs text-slate-400">Statistik aktivitas pemesanan tiket harian</p>
                            </div>
                        </div>

                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-600">
                            <i class="fa-regular fa-calendar text-slate-400"></i>
                            <span>7 Hari Terakhir</span>
                        </div>
                    </div>

                    <!-- CSS Vertical Bar Chart (Clean, Responsive, No SVG Icon) -->
                    <div class="pt-4">
                        <div class="h-48 flex items-end justify-between gap-2 sm:gap-4 border-b border-slate-100 pb-2 px-2">
                            @foreach ($tren7Hari as $item)
                                @php
                                    $heightPercent = $maxCount > 0 ? max(12, round(($item['count'] / $maxCount) * 100)) : 12;
                                    $isToday = $loop->last;
                                @endphp
                                <div class="flex-1 flex flex-col items-center h-full justify-end group relative cursor-pointer">
                                    {{-- Tooltip Hover --}}
                                    <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition pointer-events-none z-20 bg-slate-800 text-white text-[11px] rounded-xl px-2.5 py-1.5 shadow-lg whitespace-nowrap text-center">
                                        <p class="font-bold">{{ $item['count'] }} Tiket</p>
                                        <p class="text-[9.5px] text-slate-300">Rp {{ number_format($item['pendapatan'], 0, ',', '.') }}</p>
                                    </div>

                                    {{-- Value on Top of Bar --}}
                                    <span class="text-[11px] font-extrabold mb-1 {{ $item['count'] > 0 ? 'text-blue-600' : 'text-slate-300' }}">
                                        {{ $item['count'] }}
                                    </span>

                                    {{-- Bar Column --}}
                                    <div class="w-full max-w-[42px] rounded-t-xl transition-all duration-300 {{ $isToday ? 'bg-gradient-to-t from-blue-600 to-sky-400 shadow-md shadow-blue-200 group-hover:from-blue-700 group-hover:to-sky-500' : ($item['count'] > 0 ? 'bg-gradient-to-t from-sky-400 to-sky-200 group-hover:from-sky-500 group-hover:to-sky-300' : 'bg-slate-100') }}"
                                        style="height: {{ $heightPercent }}%;">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- X Axis Labels (Tanggal) -->
                        <div class="flex justify-between px-2 pt-2 text-[11px] font-semibold text-slate-400">
                            @foreach ($tren7Hari as $item)
                                <div class="flex-1 text-center truncate">
                                    <p class="{{ $loop->last ? 'text-blue-600 font-bold' : '' }}">{{ $item['tanggal'] }}</p>
                                    <p class="text-[10px] text-slate-300 font-normal">{{ $item['hari'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Pemesanan Row -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xs border border-slate-100">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-800">
                                    Status Pemesanan Tiket
                                </h2>
                                <p class="text-xs text-slate-400">Distribusi status seluruh transaksi tiket</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.tiket.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @php
                        $pctPending = $totalPemesanan > 0 ? round(($tiketPending / $totalPemesanan) * 100) : 0;
                        $pctSelesai = $totalPemesanan > 0 ? round(($tiketSelesai / $totalPemesanan) * 100) : 0;
                        $pctBatal   = $totalPemesanan > 0 ? round(($tiketBatal / $totalPemesanan) * 100) : 0;
                    @endphp

                    <!-- 3 Progress Bars List Dinamis -->
                    <div class="space-y-4">
                        <!-- Belum Digunakan / Menunggu -->
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold mb-1.5">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                    <span>Belum Digunakan (Pending)</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-extrabold text-slate-800">{{ $tiketPending }}</span>
                                    <span class="text-xs text-slate-400 font-normal">({{ $pctPending }}%)</span>
                                </div>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full transition-all duration-500" style="width: {{ $pctPending }}%;"></div>
                            </div>
                        </div>

                        <!-- Sudah Digunakan / Selesai -->
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold mb-1.5">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span>Sudah Digunakan (Selesai Naik Kapal)</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-extrabold text-slate-800">{{ $tiketSelesai }}</span>
                                    <span class="text-xs text-slate-400 font-normal">({{ $pctSelesai }}%)</span>
                                </div>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $pctSelesai }}%;"></div>
                            </div>
                        </div>

                        <!-- Dibatalkan / Void -->
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold mb-1.5">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span>Dibatalkan (Void / Salah Input)</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-extrabold text-slate-800">{{ $tiketBatal }}</span>
                                    <span class="text-xs text-slate-400 font-normal">({{ $pctBatal }}%)</span>
                                </div>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: {{ $pctBatal }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Armada Speedboat Ringkasan Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xs border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-ship"></i>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-800">Status Armada Speedboat</h2>
                                <p class="text-xs text-slate-400">Ketersediaan kapal untuk trip wisata</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.kapal.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 hover:underline">
                            Kelola Kapal &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                        @forelse ($kapals as $kapal)
                            <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100/70 text-blue-600 flex items-center justify-center text-base shrink-0">
                                        <i class="fa-solid fa-sailboat"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 text-xs truncate">{{ $kapal->nama_kapal }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ $kapal->kode_kapal }} • Kapasitas {{ $kapal->kapasitas }} orang</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    @if ($kapal->status === \App\Models\Kapal::STATUS_TERSEDIA)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                            Tersedia
                                        </span>
                                    @elseif ($kapal->status === \App\Models\Kapal::STATUS_SEDANG_BERLAYAR)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                            <span class="w-1 h-1 rounded-full bg-sky-500 animate-pulse"></span>
                                            Berlayar
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1 h-1 rounded-full bg-amber-500"></span>
                                            Maintenance
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-6 text-center text-slate-400 text-xs">
                                Belum ada data kapal terdaftar.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column: Recent Bookings & Quick Actions (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Pemesanan Terbaru Card Dinamis -->
                <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <h2 class="text-base font-bold text-slate-800">
                                Pemesanan Terbaru
                            </h2>
                        </div>
                        <a href="{{ route('admin.tiket.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <!-- List Items Dinamis dari Database -->
                    <div class="space-y-3">
                        @forelse ($pemesananTerbaru as $tiket)
                            <a href="{{ route('admin.tiket.show', $tiket) }}"
                                class="p-3.5 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/30 transition flex items-center justify-between gap-3 block group">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition">
                                            {{ $tiket->kode_tiket }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 truncate mt-1 {{ $tiket->status === -1 ? 'line-through text-slate-400' : '' }}">
                                        {{ $tiket->nama_pembeli }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        {{ $tiket->kapal ? $tiket->kapal->nama_kapal : '-' }} • {{ $tiket->is_weekend ? 'Weekend' : 'Biasa' }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    @if ($tiket->status === 1)
                                        <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded-full">
                                            Selesai
                                        </span>
                                    @elseif ($tiket->status === 0)
                                        <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold rounded-full">
                                            Pending
                                        </span>
                                    @elseif ($tiket->status === -1)
                                        <span class="inline-block px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded-full">
                                            Batal
                                        </span>
                                    @endif
                                    <p class="text-[10px] text-slate-400 mt-1">
                                        {{ $tiket->tanggal_pemesanan ? $tiket->tanggal_pemesanan->format('d M H:i') : '-' }}
                                    </p>
                                    <p class="text-xs font-extrabold text-slate-800 mt-0.5 {{ $tiket->status === -1 ? 'line-through text-slate-400' : '' }}">
                                        Rp {{ number_format($tiket->total_bayar, 0, ',', '.') }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="py-8 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-ticket text-3xl mb-2 opacity-30 block"></i>
                                Belum ada pemesanan tiket.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Action Shortcuts -->
                <div class="bg-gradient-to-br from-sky-50 via-white to-blue-50/40 rounded-3xl p-5 shadow-xs border border-sky-100/80 space-y-3">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Aksi Cepat</h3>
                            <p class="text-[11px] text-slate-400">Pintasan menu operasional kasir & operator</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="{{ route('admin.tiket.create') }}"
                            class="p-3 bg-white hover:bg-blue-600 hover:text-white rounded-2xl border border-slate-100 text-slate-700 text-xs font-bold flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                            <i class="fa-solid fa-circle-plus text-blue-600 group-hover:text-white text-base"></i>
                            <span>Buat Tiket</span>
                        </a>

                        <a href="{{ route('admin.scan.index') }}"
                            class="p-3 bg-white hover:bg-emerald-600 hover:text-white rounded-2xl border border-slate-100 text-slate-700 text-xs font-bold flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                            <i class="fa-solid fa-qrcode text-emerald-600 group-hover:text-white text-base"></i>
                            <span>Scan Tiket</span>
                        </a>

                        <a href="{{ route('admin.kapal.create') }}"
                            class="p-3 bg-white hover:bg-purple-600 hover:text-white rounded-2xl border border-slate-100 text-slate-700 text-xs font-bold flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                            <i class="fa-solid fa-ship text-purple-600 group-hover:text-white text-base"></i>
                            <span>Tambah Kapal</span>
                        </a>

                        <a href="{{ route('admin.laporan.index') }}"
                            class="p-3 bg-white hover:bg-amber-600 hover:text-white rounded-2xl border border-slate-100 text-slate-700 text-xs font-bold flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                            <i class="fa-solid fa-file-invoice-dollar text-amber-600 group-hover:text-white text-base"></i>
                            <span>Laporan Bulanan</span>
                        </a>
                    </div>
                </div>

                <!-- Info Cuaca Wahana Gedambaan (No SVG Icon) -->
                <div class="bg-gradient-to-br from-amber-50/80 via-white to-sky-50/50 rounded-3xl p-5 shadow-xs border border-amber-100/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-500 flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-cloud-sun"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-extrabold text-slate-900 leading-none block">31°C</span>
                            <span class="text-[11px] font-semibold text-slate-500 mt-1 block">Cerah Berawan • Laut Tenang</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] text-slate-400 font-medium block">Lokasi Wahana</span>
                        <span class="text-[11px] font-bold text-sky-600 flex items-center gap-1 justify-end mt-0.5">
                            <i class="fa-solid fa-location-dot text-rose-500"></i>
                            Pantai Gedambaan, Kotabaru
                        </span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
