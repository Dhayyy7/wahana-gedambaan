<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pemesanan Tiket</h1>
                <p class="text-sm text-slate-500 mt-0.5">Riwayat seluruh transaksi tiket wahana.</p>
            </div>
            <a href="{{ route('admin.tiket.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                <i class="fa-solid fa-plus"></i>
                Buat Tiket
            </a>
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

    {{-- Stats --}}
    @php
        $totalTiket  = \App\Models\Tiket::count();
        $pending     = \App\Models\Tiket::where('status', 0)->count();
        $selesai     = \App\Models\Tiket::where('status', 1)->count();
        $pendapatan  = \App\Models\Tiket::whereMonth('tanggal_pemesanan', now()->month)->sum('total_bayar');
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg shrink-0">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalTiket }}</p>
                <p class="text-xs text-slate-500 font-medium">Total Tiket</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-lg shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $pending }}</p>
                <p class="text-xs text-slate-500 font-medium">Belum Digunakan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $selesai }}</p>
                <p class="text-xs text-slate-500 font-medium">Sudah Digunakan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4 col-span-2 lg:col-span-1">
            <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 text-lg shrink-0">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-lg font-extrabold text-slate-800 leading-tight">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 font-medium">Pendapatan Bulan Ini</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        @if ($tikets->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-slate-400">
                <i class="fa-solid fa-ticket text-5xl mb-4 opacity-30"></i>
                <p class="font-semibold text-slate-500">Belum ada tiket</p>
                <p class="text-sm mt-1">Klik "Buat Tiket" untuk membuat pemesanan baru.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider">Kode Tiket</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider">Pembeli</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider hidden md:table-cell">Kapal</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider hidden sm:table-cell">Total Bayar</th>
                            <th class="px-5 py-3.5 text-center font-semibold text-slate-500 text-xs uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider hidden lg:table-cell">Waktu Pesan</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($tikets as $tiket)
                            <tr class="hover:bg-slate-50/50 transition">
                                {{-- Kode Tiket --}}
                                <td class="px-5 py-4">
                                    <span class="font-mono font-bold text-blue-600 text-xs bg-blue-50 px-2.5 py-1 rounded-lg">{{ $tiket->kode_tiket }}</span>
                                </td>
                                {{-- Pembeli --}}
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">{{ $tiket->nama_pembeli }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $tiket->no_hp }}</p>
                                </td>
                                {{-- Kapal --}}
                                <td class="px-5 py-4 hidden md:table-cell">
                                    <p class="font-medium text-slate-700">{{ $tiket->kapal->nama_kapal }}</p>
                                    <p class="text-xs text-slate-400">{{ $tiket->is_weekend ? '🌅 Weekend' : '📅 Hari Biasa' }}</p>
                                </td>
                                {{-- Total --}}
                                <td class="px-5 py-4 text-right font-semibold text-slate-800 hidden sm:table-cell">
                                    Rp {{ number_format($tiket->total_bayar, 0, ',', '.') }}
                                </td>
                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($tiket->status === 0)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Belum Digunakan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
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
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.tiket.show', $tiket) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                                            title="Lihat Detail">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.tiket.print', $tiket) }}" target="_blank"
                                            class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition"
                                            title="Cetak Struk Termal (58mm)">
                                            <i class="fa-solid fa-print text-sm"></i>
                                        </a>
                                        @if ($tiket->status === 0)
                                            <form method="POST" action="{{ route('admin.tiket.destroy', $tiket) }}"
                                                onsubmit="return confirm('Hapus tiket {{ $tiket->kode_tiket }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition"
                                                    title="Hapus">
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
</x-app-layout>
