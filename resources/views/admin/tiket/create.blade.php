<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tiket.index') }}"
                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Buat Tiket Baru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Isi data pengunjung dan pilih kapal.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        {{-- Preview Tarif Real-time --}}
        <div id="tarif-preview"
            class="hidden mb-5 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider mb-0.5">Kapal Dipilih</p>
                    <p id="preview-nama-kapal" class="font-bold text-lg"></p>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider mb-0.5">Total Bayar</p>
                    <p id="preview-total" class="font-extrabold text-2xl"></p>
                </div>
            </div>
            <div class="flex items-center gap-4 mt-3 pt-3 border-t border-blue-500/40 text-xs text-blue-100">
                <span><i class="fa-solid fa-calendar-days mr-1"></i> <span id="preview-hari"></span></span>
                <span><i class="fa-solid fa-tag mr-1"></i> Tarif: <span id="preview-tarif"></span></span>
                <span id="preview-diskon-wrapper" class="hidden"><i class="fa-solid fa-scissors mr-1"></i> Diskon: <span id="preview-diskon"></span></span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.tiket.store') }}" class="space-y-5">
            @csrf

            {{-- Pilih Kapal --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                    <i class="fa-solid fa-ship text-blue-500 text-sm"></i>
                    Pilih Kapal
                </h2>

                @if ($kapals->isEmpty())
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
                        <span>Tidak ada kapal yang tersedia saat ini. Semua kapal sedang digunakan atau maintenance.</span>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="kapal-options">
                        @foreach ($kapals as $kapal)
                            <label for="kapal_{{ $kapal->id }}"
                                class="kapal-card relative flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-blue-400 transition-all duration-150 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50"
                                data-tarif-biasa="{{ $kapal->tarif_biasa }}"
                                data-tarif-weekend="{{ $kapal->tarif_weekend }}"
                                data-diskon="{{ $kapal->diskon }}"
                                data-nama="{{ $kapal->nama_kapal }}>">
                                <input type="radio" id="kapal_{{ $kapal->id }}" name="kapal_id"
                                    value="{{ $kapal->id }}"
                                    class="sr-only kapal-radio"
                                    {{ old('kapal_id') == $kapal->id ? 'checked' : '' }} />
                                @if ($kapal->foto)
                                    <img src="{{ Storage::url($kapal->foto) }}" alt="{{ $kapal->nama_kapal }}"
                                        class="w-14 h-14 rounded-xl object-cover border border-slate-100 shrink-0" />
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-blue-400 shrink-0">
                                        <i class="fa-solid fa-ship text-xl"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 text-sm">{{ $kapal->nama_kapal }}</p>
                                    <p class="text-xs text-slate-500 font-mono">{{ $kapal->kode_kapal }}</p>
                                    <p class="text-xs text-blue-600 font-semibold mt-1">
                                        Rp {{ number_format($kapal->tarif_biasa, 0, ',', '.') }}
                                        <span class="text-slate-400 font-normal">/ biasa</span>
                                    </p>
                                </div>
                                {{-- Checkmark --}}
                                <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center transition-all
                                    kapal-check opacity-0">
                                    <i class="fa-solid fa-check text-[10px] text-white"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('kapal_id')
                        <p class="text-red-500 text-xs flex items-center gap-1 mt-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                @endif
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
                        value="{{ old('nama_pembeli') }}"
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
                        <input type="tel" id="no_hp" name="no_hp"
                            value="{{ old('no_hp') }}"
                            placeholder="08xxxxxxxxxx"
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
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('alamat') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition resize-none">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.tiket.index') }}"
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition">
                    Batal
                </a>
                <button type="submit" id="btn-submit" {{ $kapals->isEmpty() ? 'disabled' : '' }}
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold px-7 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                    <i class="fa-solid fa-ticket"></i>
                    Buat Tiket & Cetak
                </button>
            </div>
        </form>
    </div>

    <script>
        // Data kapal dari server
        const isWeekend = {{ now()->isWeekend() ? 'true' : 'false' }};

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        function updatePreview(label) {
            const tarif      = isWeekend ? parseFloat(label.dataset.tarifWeekend) : parseFloat(label.dataset.tarifBiasa);
            const diskon     = parseFloat(label.dataset.diskon) || 0;
            const total      = Math.max(0, tarif - diskon);
            const nama       = label.dataset.nama.replace('>', ''); // cleanup

            document.getElementById('preview-nama-kapal').textContent = nama;
            document.getElementById('preview-total').textContent      = formatRupiah(total);
            document.getElementById('preview-hari').textContent       = isWeekend ? 'Weekend' : 'Hari Biasa';
            document.getElementById('preview-tarif').textContent      = formatRupiah(tarif);

            const diskonWrapper = document.getElementById('preview-diskon-wrapper');
            if (diskon > 0) {
                document.getElementById('preview-diskon').textContent = formatRupiah(diskon);
                diskonWrapper.classList.remove('hidden');
            } else {
                diskonWrapper.classList.add('hidden');
            }

            document.getElementById('tarif-preview').classList.remove('hidden');
        }

        // Update check state visual
        function updateCheckmarks() {
            document.querySelectorAll('.kapal-card').forEach(card => {
                const radio = card.querySelector('.kapal-radio');
                const check = card.querySelector('.kapal-check');
                if (radio.checked) {
                    check.classList.remove('opacity-0');
                    check.classList.add('opacity-100', 'bg-blue-600', 'border-blue-600');
                } else {
                    check.classList.add('opacity-0');
                    check.classList.remove('opacity-100', 'bg-blue-600', 'border-blue-600');
                }
            });
        }

        document.querySelectorAll('.kapal-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const label = this.closest('.kapal-card');
                updatePreview(label);
                updateCheckmarks();
            });
        });

        // Init jika ada old value
        const checkedRadio = document.querySelector('.kapal-radio:checked');
        if (checkedRadio) {
            updatePreview(checkedRadio.closest('.kapal-card'));
            updateCheckmarks();
        }
    </script>
</x-app-layout>
