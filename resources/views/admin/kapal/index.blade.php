<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Kapal</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola daftar speed boat yang tersedia di wahana.</p>
            </div>
            <a href="{{ route('admin.kapal.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                <i class="fa-solid fa-plus"></i>
                Tambah Kapal
            </a>
        </div>
    </x-slot>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div id="flash-success"
            class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-500 text-base shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()"
                class="ml-auto text-emerald-400 hover:text-emerald-600 transition"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    @if (session('error'))
        <div id="flash-error"
            class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-base shrink-0"></i>
            <span>{{ session('error') }}</span>
            <button onclick="document.getElementById('flash-error').remove()"
                class="ml-auto text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    {{-- Stats Summary --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        @php
            $totalKapal   = $kapals->total();
            $tersedia     = \App\Models\Kapal::where('status', 1)->count();
            $maintenance     = \App\Models\Kapal::where('status', 3)->count();
        @endphp
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg shrink-0">
                <i class="fa-solid fa-ship"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalKapal }}</p>
                <p class="text-xs text-slate-500 font-medium">Total Kapal</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $tersedia }}</p>
                <p class="text-xs text-slate-500 font-medium">Tersedia</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4 col-span-2 sm:col-span-1">
            <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center text-orange-500 text-lg shrink-0 animate-pulse">
                <i class="fa-solid fa-gear"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $maintenance }}</p>
                <p class="text-xs text-slate-500 font-medium">Sedang Maintenance</p>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        @if ($kapals->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-slate-400">
                <i class="fa-solid fa-ship text-5xl mb-4 opacity-30"></i>
                <p class="font-semibold text-slate-500">Belum ada kapal terdaftar</p>
                <p class="text-sm mt-1">Klik tombol "Tambah Kapal" untuk memulai.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider">Kapal</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider hidden sm:table-cell">Kode</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider hidden md:table-cell">Tarif Biasa</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider hidden md:table-cell">Tarif Weekend</th>
                            <th class="px-5 py-3.5 text-center font-semibold text-slate-500 text-xs uppercase tracking-wider hidden lg:table-cell">Kapasitas</th>
                            <th class="px-5 py-3.5 text-center font-semibold text-slate-500 text-xs uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($kapals as $kapal)
                            <tr class="hover:bg-slate-50/50 transition group">
                                {{-- Nama & Foto --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($kapal->foto)
                                            <img src="{{ Storage::url($kapal->foto) }}" alt="{{ $kapal->nama_kapal }}"
                                                class="w-10 h-10 rounded-xl object-cover border border-slate-100 shrink-0" />
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-400 shrink-0">
                                                <i class="fa-solid fa-ship text-base"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $kapal->nama_kapal }}</p>
                                            <p class="text-xs text-slate-400 sm:hidden">{{ $kapal->kode_kapal }}</p>
                                        </div>
                                    </div>
                                </td>
                                {{-- Kode --}}
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    <span class="bg-slate-100 text-slate-600 text-xs font-mono font-bold px-2.5 py-1 rounded-lg">{{ $kapal->kode_kapal }}</span>
                                </td>
                                {{-- Tarif Biasa --}}
                                <td class="px-5 py-4 text-right text-slate-700 font-medium hidden md:table-cell">
                                    Rp {{ number_format($kapal->tarif_biasa, 0, ',', '.') }}
                                </td>
                                {{-- Tarif Weekend --}}
                                <td class="px-5 py-4 text-right text-slate-700 font-medium hidden md:table-cell">
                                    Rp {{ number_format($kapal->tarif_weekend, 0, ',', '.') }}
                                </td>
                                {{-- Kapasitas --}}
                                <td class="px-5 py-4 text-center hidden lg:table-cell">
                                    <span class="text-slate-600 font-medium">{{ $kapal->kapasitas }} <span class="text-slate-400 font-normal text-xs">pax</span></span>
                                </td>
                                {{-- Status Badge --}}
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $statusClasses = match($kapal->status) {
                                            1 => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                            2 => 'bg-sky-50 text-sky-700 border border-sky-200',
                                            3 => 'bg-rose-50 text-rose-700 border border-rose-200',
                                            default => 'bg-slate-50 text-slate-600 border border-slate-200',
                                        };
                                        $statusLabel = match($kapal->status) {
                                            1 => 'Tersedia',
                                            2 => 'Berlayar',
                                            3 => 'Maintenance',
                                            default => 'Tidak Diketahui',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusClasses }} {{ $kapal->status === 2 ? 'animate-pulse' : '' }}">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            {{ $kapal->status === 1 ? 'bg-emerald-500' : ($kapal->status === 2 ? 'bg-sky-500' : 'bg-rose-500') }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.kapal.edit', $kapal) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.kapal.destroy', $kapal) }}"
                                            onsubmit="return confirmAction(event, {
                                                title: 'Hapus Unit Kapal?',
                                                text: 'Yakin ingin menghapus kapal {{ addslashes($kapal->nama_kapal) }} ({{ $kapal->kode_kapal }})? Data kapal ini akan dihapus permanen.',
                                                type: 'danger',
                                                confirmText: 'Ya, Hapus Kapal',
                                                cancelText: 'Batal'
                                            })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition"
                                                title="Hapus">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($kapals->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $kapals->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
