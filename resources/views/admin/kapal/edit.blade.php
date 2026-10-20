<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.kapal.index') }}"
                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Kapal</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Memperbarui data <span class="font-semibold text-slate-700">{{ $kapal->nama_kapal }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.kapal.update', $kapal) }}" enctype="multipart/form-data"
        class="max-w-3xl mx-auto space-y-6">
        @csrf
        @method('PATCH')

        {{-- Informasi Dasar --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-5">
            <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-500 text-sm"></i>
                Informasi Dasar
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="kode_kapal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Kode Kapal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="kode_kapal" name="kode_kapal"
                        value="{{ old('kode_kapal', $kapal->kode_kapal) }}"
                        placeholder="Contoh: SB-01"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('kode_kapal') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition font-mono" />
                    @error('kode_kapal')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_kapal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Kapal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_kapal" name="nama_kapal"
                        value="{{ old('nama_kapal', $kapal->nama_kapal) }}"
                        placeholder="Contoh: Speed Boat Darmawan"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('nama_kapal') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    @error('nama_kapal')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="kapasitas" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Kapasitas <span class="text-red-500">*</span>
                        <span class="font-normal text-slate-400 text-xs">(penumpang)</span>
                    </label>
                    <input type="number" id="kapasitas" name="kapasitas"
                        value="{{ old('kapasitas', $kapal->kapasitas) }}"
                        min="1" max="100"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('kapasitas') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    @error('kapasitas')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Status Kapal <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('status') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        <option value="1" {{ old('status', $kapal->status) == 1 ? 'selected' : '' }}>✅ Tersedia</option>
                        <option value="3" {{ old('status', $kapal->status) == 3 ? 'selected' : '' }}>🔧 Maintenance</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Deskripsi / Spesifikasi
                    <span class="font-normal text-slate-400 text-xs">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    placeholder="Tuliskan spesifikasi atau keterangan tambahan..."
                    class="w-full px-4 py-2.5 text-sm rounded-xl border @error('deskripsi') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition resize-none">{{ old('deskripsi', $kapal->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tarif & Diskon --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-5">
            <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-tag text-blue-500 text-sm"></i>
                Tarif & Diskon
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="tarif_biasa" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tarif Biasa (Rp) <span class="text-red-500">*</span>
                        <span class="font-normal text-slate-400 text-xs block">Senin – Jumat</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm font-medium pointer-events-none">Rp</span>
                        <input type="number" id="tarif_biasa" name="tarif_biasa"
                            value="{{ old('tarif_biasa', $kapal->tarif_biasa) }}"
                            min="0" step="1000"
                            class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border @error('tarif_biasa') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    </div>
                    @error('tarif_biasa')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tarif_weekend" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tarif Weekend (Rp) <span class="text-red-500">*</span>
                        <span class="font-normal text-slate-400 text-xs block">Sabtu – Minggu</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm font-medium pointer-events-none">Rp</span>
                        <input type="number" id="tarif_weekend" name="tarif_weekend"
                            value="{{ old('tarif_weekend', $kapal->tarif_weekend) }}"
                            min="0" step="1000"
                            class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border @error('tarif_weekend') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    </div>
                    @error('tarif_weekend')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="diskon" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Diskon (Rp)
                        <span class="font-normal text-slate-400 text-xs block">0 = tidak ada diskon</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm font-medium pointer-events-none">Rp</span>
                        <input type="number" id="diskon" name="diskon"
                            value="{{ old('diskon', $kapal->diskon) }}"
                            min="0" step="1000"
                            class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border @error('diskon') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    </div>
                    @error('diskon')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Foto Kapal --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
            <h2 class="text-base font-bold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-image text-blue-500 text-sm"></i>
                Foto Kapal
            </h2>

            @if ($kapal->foto)
                <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <img src="{{ Storage::url($kapal->foto) }}" alt="{{ $kapal->nama_kapal }}"
                        class="h-16 w-20 rounded-lg object-cover border border-slate-200 shrink-0" />
                    <div class="text-sm">
                        <p class="font-semibold text-slate-700">Foto saat ini</p>
                        <p class="text-slate-400 text-xs mt-0.5">Upload foto baru untuk menggantinya</p>
                    </div>
                </div>
            @endif

            <label for="foto"
                class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-2xl p-6 cursor-pointer transition group">
                <div id="foto-preview-wrapper" class="hidden mb-3">
                    <img id="foto-preview" src="#" alt="Preview foto baru"
                        class="h-24 rounded-xl object-cover shadow-sm" />
                </div>
                <div id="foto-placeholder" class="flex flex-col items-center text-slate-400 group-hover:text-blue-500 transition">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">{{ $kapal->foto ? 'Ganti foto' : 'Upload foto' }}</p>
                    <p class="text-xs mt-0.5">JPG, PNG, WebP – Maks 2MB</p>
                </div>
                <input type="file" id="foto" name="foto" accept="image/*" class="hidden"
                    onchange="previewFoto(this)" />
            </label>
            @error('foto')
                <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.kapal.index') }}"
                class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-7 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>

    <script>
        function previewFoto(input) {
            const preview = document.getElementById('foto-preview');
            const previewWrapper = document.getElementById('foto-preview-wrapper');
            const placeholder = document.getElementById('foto-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewWrapper.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
