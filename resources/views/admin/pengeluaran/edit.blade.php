<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pengeluaran.index') }}"
                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Catatan Pengeluaran</h1>
                <p class="text-sm text-slate-500 mt-0.5">Perbarui data pengeluaran: <strong>{{ $pengeluaran->kode_pengeluaran }}</strong></p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.pengeluaran.update', $pengeluaran) }}"
            class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-xs space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Tanggal Pengeluaran --}}
                <div>
                    <label for="tanggal_pengeluaran" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tanggal Pengeluaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="tanggal_pengeluaran" name="tanggal_pengeluaran"
                        value="{{ old('tanggal_pengeluaran', $pengeluaran->tanggal_pengeluaran?->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm py-2.5 px-3.5 transition" />
                    @error('tanggal_pengeluaran')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nominal (Rp) --}}
                <div>
                    <label for="nominal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nominal (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm font-semibold pointer-events-none">Rp</span>
                        <input type="number" id="nominal" name="nominal"
                            value="{{ old('nominal', (int) $pengeluaran->nominal) }}"
                            min="1" step="1000" required
                            class="w-full pl-10 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm py-2.5 pr-3.5 transition" />
                    </div>
                    @error('nominal')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Pengeluaran --}}
            <div>
                <label for="nama_pengeluaran" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Nama / Keperluan Pengeluaran <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nama_pengeluaran" name="nama_pengeluaran"
                    value="{{ old('nama_pengeluaran', $pengeluaran->nama_pengeluaran) }}"
                    required
                    class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm py-2.5 px-3.5 transition" />
                @error('nama_pengeluaran')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Keterangan Tambahan <span class="text-slate-400 font-normal text-xs">(Opsional)</span>
                </label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm py-2.5 px-3.5 transition">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.pengeluaran.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit"
                    style="background-color: #2563eb; color: #ffffff;"
                    class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm shadow-blue-200 transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

