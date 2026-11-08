<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ request('redirect_to') === 'laporan' ? route('admin.laporan.index') : route('admin.tiket.index') }}"
                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Data Tiket</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Perbarui jenis pembayaran atau informasi pengunjung untuk tiket <span class="font-mono font-bold text-blue-600">{{ $tiket->kode_tiket }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Ringkasan Info Tiket (Read-only) --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider mb-0.5">Wahana & Kode Tiket</p>
                    <p class="font-bold text-lg">{{ $tiket->kapal->nama_kapal ?? '-' }}</p>
                    <p class="font-mono text-xs text-blue-100 mt-0.5">{{ $tiket->kode_tiket }}</p>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider mb-0.5">Total Bayar</p>
                    <p class="font-extrabold text-2xl">Rp {{ number_format($tiket->total_bayar, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-4 mt-3 pt-3 border-t border-blue-500/40 text-xs text-blue-100">
                <span><i class="fa-regular fa-clock mr-1"></i> Waktu Pesan: <strong>{{ $tiket->tanggal_pemesanan?->format('d M Y, H:i') }} WIB</strong></span>
                <span><i class="fa-solid fa-calendar-days mr-1"></i> {{ $tiket->is_weekend ? 'Weekend' : 'Hari Biasa' }}</span>
                <span><i class="fa-solid fa-circle-info mr-1"></i> Status: <strong>{{ $tiket->status_label }}</strong></span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.tiket.update', $tiket) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="redirect_to" value="{{ request('redirect_to', 'tiket') }}">

            {{-- Jenis Pembayaran (Checklist Tunai / QRIS) --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
                <div>
                    <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-wallet text-blue-500 text-sm"></i>
                        Jenis Pembayaran
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pilih jenis pembayaran (Tunai atau QRIS) untuk mempermudah rekapitulasi laporan.</p>
                </div>

                @php
                    $selectedMetode = old('metode_pembayaran', $tiket->metode_pembayaran);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="pembayaran-options">
                    {{-- Opsi Tunai --}}
                    <label for="bayar_tunai"
                        class="bayar-card relative flex items-center gap-3.5 p-4 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-emerald-400 transition-all duration-150 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                        <input type="radio" id="bayar_tunai" name="metode_pembayaran" value="tunai"
                            class="sr-only bayar-radio"
                            {{ $selectedMetode === 'tunai' ? 'checked' : '' }} />
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-slate-800 text-sm">Tunai (Cash)</p>
                            <p class="text-xs text-slate-400">Pembayaran uang tunai</p>
                        </div>
                        <div class="bayar-check absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center transition-all opacity-0">
                            <i class="fa-solid fa-check text-[10px] text-white"></i>
                        </div>
                    </label>

                    {{-- Opsi QRIS --}}
                    <label for="bayar_qris"
                        class="bayar-card relative flex items-center gap-3.5 p-4 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-sky-400 transition-all duration-150 has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50/50">
                        <input type="radio" id="bayar_qris" name="metode_pembayaran" value="qris"
                            class="sr-only bayar-radio"
                            {{ $selectedMetode === 'qris' ? 'checked' : '' }} />
                        <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-slate-800 text-sm">QRIS</p>
                            <p class="text-xs text-slate-400">Pembayaran scan QRIS</p>
                        </div>
                        <div class="bayar-check absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center transition-all opacity-0">
                            <i class="fa-solid fa-check text-[10px] text-white"></i>
                        </div>
                    </label>
                </div>

                @if ($tiket->metode_pembayaran === null)
                    <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 px-3.5 py-2 rounded-xl flex items-center gap-2">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Tiket ini belum memiliki data jenis pembayaran (kosong). Silakan pilih <strong>Tunai</strong> atau <strong>QRIS</strong> di atas.</span>
                    </p>
                @endif

                @if (($relatedCount ?? 0) > 0)
                    <label class="flex items-start gap-3 p-3.5 rounded-xl bg-blue-50/70 border border-blue-200 cursor-pointer hover:bg-blue-50 transition">
                        <input type="checkbox" name="apply_same_buyer" value="1" checked
                            class="mt-0.5 rounded border-blue-300 text-blue-600 focus:ring-blue-500" />
                        <span class="text-xs text-blue-800 leading-relaxed">
                            Terapkan juga jenis pembayaran ini sekaligus ke <strong>{{ $relatedCount }} tiket lainnya</strong> atas nama <strong>{{ $tiket->nama_pembeli }}</strong> pada tanggal {{ $tiket->tanggal_pemesanan?->format('d M Y') }}.
                        </span>
                    </label>
                @endif

                @error('metode_pembayaran')
                    <p class="text-red-500 text-xs flex items-center gap-1 mt-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Data Pengunjung --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                    <i class="fa-solid fa-user text-blue-500 text-sm"></i>
                    Data Pengunjung
                </h2>

                <div>
                    <label for="nama_pembeli" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_pembeli" name="nama_pembeli"
                        value="{{ old('nama_pembeli', $tiket->nama_pembeli) }}"
                        placeholder="Masukkan nama pengunjung"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('nama_pembeli') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    @error('nama_pembeli')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nomor HP <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </span>
                        <input type="text" id="no_hp" name="no_hp"
                            value="{{ old('no_hp', $tiket->no_hp) }}"
                            placeholder="08xxxxxxxxxx atau -"
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border @error('no_hp') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    </div>
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Alamat
                        <span class="font-normal text-slate-400 text-xs">(opsional)</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="2"
                        placeholder="Masukkan alamat pengunjung"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('alamat') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition resize-none">{{ old('alamat', $tiket->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ request('redirect_to') === 'laporan' ? route('admin.laporan.index') : route('admin.tiket.index') }}"
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition">
                    Batal
                </a>
                <button type="submit"
                    style="background-color: #2563eb; color: #ffffff;"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-7 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateBayarCheckmarks() {
            document.querySelectorAll('.bayar-card').forEach(card => {
                const radio = card.querySelector('.bayar-radio');
                const check = card.querySelector('.bayar-check');
                if (radio.checked) {
                    const colorClass = radio.value === 'qris' ? ['bg-sky-600', 'border-sky-600'] : ['bg-emerald-600', 'border-emerald-600'];
                    check.classList.remove('opacity-0', 'bg-sky-600', 'border-sky-600', 'bg-emerald-600', 'border-emerald-600');
                    check.classList.add('opacity-100', ...colorClass);
                } else {
                    check.classList.add('opacity-0');
                    check.classList.remove('opacity-100', 'bg-sky-600', 'border-sky-600', 'bg-emerald-600', 'border-emerald-600');
                }
            });
        }

        document.querySelectorAll('.bayar-radio').forEach(radio => {
            radio.addEventListener('change', updateBayarCheckmarks);
        });

        updateBayarCheckmarks();
    </script>
</x-app-layout>

