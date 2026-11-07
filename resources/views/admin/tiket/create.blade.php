<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tiket.index') }}"
                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Buat Tiket Baru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Isi data pengunjung dan pilih wahana.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        {{-- Preview Tarif Real-time --}}
        <div id="tarif-preview"
            class="hidden mb-5 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider mb-0.5">Wahana Dipilih</p>
                    <p id="preview-nama-kapal" class="font-bold text-lg"></p>
                </div>
                <div class="text-right">
                    <p id="preview-label-total" class="text-blue-100 text-xs font-medium uppercase tracking-wider mb-0.5">Total Bayar</p>
                    <p id="preview-total" class="font-extrabold text-2xl"></p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-4 mt-3 pt-3 border-t border-blue-500/40 text-xs text-blue-100">
                <span><i class="fa-solid fa-calendar-days mr-1"></i> <span id="preview-hari"></span></span>
                <span><i class="fa-solid fa-tag mr-1"></i> Tarif/Tiket: <span id="preview-tarif"></span></span>
                <span><i class="fa-solid fa-layer-group mr-1"></i> Jumlah: <strong id="preview-jumlah">1 Tiket</strong></span>
                <span id="preview-diskon-wrapper" class="hidden"><i class="fa-solid fa-scissors mr-1"></i> Diskon/Tiket: <span id="preview-diskon"></span></span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.tiket.store') }}" class="space-y-5">
            @csrf

            {{-- Pilih Wahana --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                    <i class="fa-solid fa-ship text-blue-500 text-sm"></i>
                    Pilih Wahana
                </h2>

                @if ($kapals->isEmpty())
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
                        <span>Tidak ada wahana yang tersedia saat ini. Semua wahana sedang digunakan atau maintenance.</span>
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

            {{-- Jumlah Tiket (Sekali Buat Bisa Langsung Banyak Tiket) --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-blue-500 text-sm"></i>
                            Jumlah Tiket
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Buat beberapa tiket sekaligus dengan data pemesan yang sama.</p>
                    </div>

                    {{-- Stepper Input --}}
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="adjustJumlahTiket(-1)"
                            class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-base flex items-center justify-center transition cursor-pointer">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                        <input type="number" id="jumlah_tiket" name="jumlah_tiket"
                            value="{{ old('jumlah_tiket', 1) }}" min="1" max="50" required
                            oninput="onJumlahTiketChange()"
                            class="w-20 text-center font-extrabold text-base text-slate-800 rounded-xl border border-slate-200 py-2 px-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none" />
                        <button type="button" onclick="adjustJumlahTiket(1)"
                            class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-base flex items-center justify-center transition cursor-pointer">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- Tombol Pilih Cepat (1, 2, 3, 4, 5, 10 Tiket) --}}
                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <span class="text-xs text-slate-400 font-medium mr-1">Pilih Cepat:</span>
                    @foreach ([1, 2, 3, 4, 5, 10] as $preset)
                        <button type="button" onclick="setJumlahTiket({{ $preset }})"
                            data-preset="{{ $preset }}"
                            class="preset-jumlah-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition cursor-pointer {{ (int) old('jumlah_tiket', 1) === $preset ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-600 border-slate-200' }}">
                            {{ $preset }} Tiket
                        </button>
                    @endforeach
                </div>

                <div id="multi-ticket-info" class="hidden flex items-start gap-2.5 bg-blue-50/80 border border-blue-100 text-blue-700 text-xs px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 shrink-0"></i>
                    <span>
                        Sistem akan membuat <strong id="multi-ticket-count-label">5</strong> tiket sekaligus. Data <strong>Nama Pembeli, Nomor HP, dan Alamat</strong> di bawah akan otomatis disalin ke semua tiket (masing-masing tiket tetap memiliki Kode Tiket & QR Code unik).
                    </span>
                </div>

                @error('jumlah_tiket')
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
                    style="background-color: #2563eb; color: #ffffff;"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold px-7 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition cursor-pointer">
                    <i class="fa-solid fa-ticket"></i>
                    <span id="btn-submit-text">Buat Tiket & Cetak</span>
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

        function getJumlahTiket() {
            const input = document.getElementById('jumlah_tiket');
            const val = parseInt(input ? input.value : 1, 10);
            if (isNaN(val) || val < 1) return 1;
            if (val > 50) return 50;
            return val;
        }

        function setJumlahTiket(val) {
            const input = document.getElementById('jumlah_tiket');
            if (input) {
                input.value = val;
                onJumlahTiketChange();
            }
        }

        function adjustJumlahTiket(delta) {
            const current = getJumlahTiket();
            const next = Math.max(1, Math.min(50, current + delta));
            setJumlahTiket(next);
        }

        function onJumlahTiketChange() {
            const jumlah = getJumlahTiket();

            // Update highlight tombol preset
            document.querySelectorAll('.preset-jumlah-btn').forEach(btn => {
                const preset = parseInt(btn.getAttribute('data-preset'), 10);
                if (preset === jumlah) {
                    btn.className = 'preset-jumlah-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition cursor-pointer bg-blue-600 text-white border-blue-600 shadow-xs';
                    btn.style.backgroundColor = '#2563eb';
                    btn.style.color = '#ffffff';
                } else {
                    btn.className = 'preset-jumlah-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition cursor-pointer bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-600 border-slate-200';
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                }
            });

            // Tampilkan info multi-tiket jika > 1
            const infoBox = document.getElementById('multi-ticket-info');
            const countLabel = document.getElementById('multi-ticket-count-label');
            const btnSubmitText = document.getElementById('btn-submit-text');

            if (jumlah > 1) {
                if (infoBox) infoBox.classList.remove('hidden');
                if (countLabel) countLabel.textContent = jumlah;
                if (btnSubmitText) btnSubmitText.textContent = `Buat ${jumlah} Tiket & Cetak`;
            } else {
                if (infoBox) infoBox.classList.add('hidden');
                if (btnSubmitText) btnSubmitText.textContent = 'Buat Tiket & Cetak';
            }

            // Refresh preview harga jika wahana sudah dipilih
            const checkedRadio = document.querySelector('.kapal-radio:checked');
            if (checkedRadio) {
                updatePreview(checkedRadio.closest('.kapal-card'));
            }
        }

        function updatePreview(label) {
            const jumlah     = getJumlahTiket();
            const tarif      = isWeekend ? parseFloat(label.dataset.tarifWeekend) : parseFloat(label.dataset.tarifBiasa);
            const diskon     = parseFloat(label.dataset.diskon) || 0;
            const hargaSatu  = Math.max(0, tarif - diskon);
            const totalSemua = hargaSatu * jumlah;
            const nama       = label.dataset.nama.replace('>', ''); // cleanup

            document.getElementById('preview-nama-kapal').textContent  = nama;
            document.getElementById('preview-label-total').textContent = jumlah > 1 ? `Total Bayar (${jumlah} Tiket)` : 'Total Bayar';
            document.getElementById('preview-total').textContent       = formatRupiah(totalSemua);
            document.getElementById('preview-hari').textContent        = isWeekend ? 'Weekend' : 'Hari Biasa';
            document.getElementById('preview-tarif').textContent       = formatRupiah(hargaSatu);
            document.getElementById('preview-jumlah').textContent      = `${jumlah} Tiket`;

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

        // Init saat halaman dimuat
        onJumlahTiketChange();
        const checkedRadio = document.querySelector('.kapal-radio:checked');
        if (checkedRadio) {
            updatePreview(checkedRadio.closest('.kapal-card'));
            updateCheckmarks();
        }
    </script>
</x-app-layout>
