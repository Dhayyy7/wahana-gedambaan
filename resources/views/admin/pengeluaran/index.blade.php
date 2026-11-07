<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Catatan Pengeluaran</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola dan rekap seluruh catatan pengeluaran operasional wahana.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Tombol Export Excel Terformat --}}
                <a href="{{ route('admin.pengeluaran.export-excel', request()->all()) }}"
                    style="background-color: #059669; color: #ffffff;"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white text-sm font-bold px-4 py-2.5 rounded-2xl shadow-sm shadow-emerald-200 transition"
                    title="Download Laporan Pengeluaran Excel (.xls)">
                    <i class="fa-solid fa-file-excel text-base"></i>
                    <span>Export Excel</span>
                </a>

                {{-- Tombol Export CSV --}}
                <a href="{{ route('admin.pengeluaran.export-csv', request()->all()) }}"
                    class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 active:scale-98 text-slate-700 text-sm font-semibold px-3.5 py-2.5 rounded-2xl border border-slate-200/80 transition"
                    title="Download File CSV">
                    <i class="fa-solid fa-file-csv text-base text-slate-500"></i>
                    <span>CSV</span>
                </a>

                {{-- Tombol Tambah Pengeluaran --}}
                <a href="{{ route('admin.pengeluaran.create') }}"
                    style="background-color: #2563eb; color: #ffffff;"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white text-sm font-bold px-5 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Catat Pengeluaran</span>
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $periodeTeks = ($bulan !== 'all' && isset($namaBulan[(int) $bulan]))
            ? $namaBulan[(int) $bulan] . ' ' . $tahun
            : 'Semua Bulan ' . $tahun;
    @endphp

    {{-- Flash Messages --}}
    @if (session('success'))
        <div id="flash-success"
            class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-500 shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
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
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-rose-400 hover:text-rose-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Card 1: Total Pengeluaran Periode Terpilih --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 text-lg shrink-0">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p class="text-lg font-extrabold text-slate-800 leading-tight">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Total Pengeluaran ({{ $periodeTeks }})</p>
            </div>
        </div>

        {{-- Card 2: Pengeluaran Hari Ini --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-lg shrink-0">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <div>
                <p class="text-lg font-extrabold text-slate-800 leading-tight">Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Pengeluaran Hari Ini</p>
            </div>
        </div>

        {{-- Card 3: Total Catatan --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg shrink-0">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800 leading-tight">{{ $totalCatatan }}</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Jumlah Catatan Pengeluaran</p>
            </div>
        </div>

        {{-- Card 4: Pengeluaran Terbesar --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 text-lg shrink-0">
                <i class="fa-solid fa-arrow-up-wide-short"></i>
            </div>
            <div>
                <p class="text-lg font-extrabold text-slate-800 leading-tight">Rp {{ number_format($pengeluaranTerbesar, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Pengeluaran Terbesar</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end">
            {{-- Pencarian --}}
            <div class="lg:col-span-2">
                <label for="search" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-slate-400"></i> Cari Pengeluaran
                </label>
                <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                    placeholder="Cari kode, nama pengeluaran, atau keterangan..."
                    class="w-full px-3.5 py-2.5 bg-slate-50/70 focus:bg-white text-xs sm:text-sm text-slate-700 placeholder-slate-400 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition outline-none" />
            </div>

            {{-- Filter Bulan --}}
            <div>
                <label for="bulan" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    <i class="fa-regular fa-calendar mr-1 text-slate-400"></i> Bulan
                </label>
                <select name="bulan" id="bulan"
                    class="w-full text-xs sm:text-sm font-medium rounded-2xl border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 transition">
                    <option value="all" {{ (string) $bulan === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach ($namaBulan as $num => $nama)
                        <option value="{{ $num }}" {{ (string) $bulan === (string) $num ? 'selected' : '' }}>
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

            {{-- Tombol Terapkan & Reset --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                    style="background-color: #2563eb; color: #ffffff;"
                    class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-sm shadow-blue-200 transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('admin.pengeluaran.index') }}"
                    class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl transition"
                    title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel Catatan Pengeluaran --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-slate-500">
            <div>
                Menampilkan <strong class="text-slate-800 font-semibold">{{ $pengeluarans->firstItem() ?? 0 }} - {{ $pengeluarans->lastItem() ?? 0 }}</strong>
                dari <strong class="text-slate-800 font-semibold">{{ $pengeluarans->total() }}</strong> catatan pengeluaran ({{ $periodeTeks }})
            </div>
            <div class="font-bold text-rose-600">
                Total: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </div>
        </div>

        @if ($pengeluarans->isEmpty())
            <div class="py-20 text-center text-slate-400">
                <div class="w-16 h-16 rounded-3xl bg-slate-50 flex items-center justify-center mx-auto mb-3 text-2xl text-slate-400 border border-slate-100">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <p class="text-sm font-bold text-slate-600">Belum ada catatan pengeluaran</p>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    Tidak ditemukan data pengeluaran pada periode atau kata kunci pencarian ini.
                </p>
                <a href="{{ route('admin.pengeluaran.create') }}"
                    style="background-color: #2563eb; color: #ffffff;"
                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Catat Pengeluaran Pertama</span>
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">No</th>
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Nama Pengeluaran</th>
                            <th class="px-5 py-3.5 hidden md:table-cell">Keterangan</th>
                            <th class="px-5 py-3.5 hidden lg:table-cell">Dicatat Oleh</th>
                            <th class="px-5 py-3.5 text-right">Nominal</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pengeluarans as $index => $item)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3.5 text-slate-400 font-mono text-xs">
                                    {{ $pengeluarans->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-xl text-xs border border-rose-100">
                                        {{ $item->kode_pengeluaran }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-xs whitespace-nowrap">
                                    {{ $item->tanggal_pengeluaran ? $item->tanggal_pengeluaran->format('d M Y') : '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-bold text-slate-800">{{ $item->nama_pengeluaran }}</p>
                                    @if ($item->keterangan)
                                        <p class="text-xs text-slate-400 mt-0.5 md:hidden line-clamp-1">{{ $item->keterangan }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs hidden md:table-cell max-w-xs">
                                    {{ $item->keterangan ?: '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs hidden lg:table-cell">
                                    {{ $item->user ? $item->user->name : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-extrabold text-rose-600 whitespace-nowrap">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.pengeluaran.edit', $item) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                                            title="Edit Pengeluaran">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>

                                        @if (auth()->user()->isSuperadmin())
                                            <form method="POST" action="{{ route('admin.pengeluaran.destroy', $item) }}"
                                                onsubmit="return confirmAction(event, {
                                                    title: 'Hapus Catatan Pengeluaran?',
                                                    text: 'Apakah Anda yakin ingin menghapus pengeluaran {{ $item->nama_pengeluaran }} (Rp {{ number_format($item->nominal, 0, ',', '.') }})?',
                                                    type: 'danger',
                                                    confirmText: 'Ya, Hapus',
                                                    cancelText: 'Batal'
                                                })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer"
                                                    title="Hapus Pengeluaran (Superadmin)">
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

            @if ($pengeluarans->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $pengeluarans->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>

