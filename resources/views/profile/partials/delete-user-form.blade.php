<section class="space-y-5">
    <header class="flex items-center gap-3.5 pb-5 border-b border-rose-100">
        <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-rose-700">
                Zona Bahaya: Hapus Akun
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Setelah akun Anda dihapus, semua data dan akses Anda akan dihapus secara permanen.
            </p>
        </div>
    </header>

    <div class="bg-rose-50/60 border border-rose-100 rounded-2xl p-4 text-xs text-rose-700 leading-relaxed">
        Harap berhati-hati. Sebelum menghapus akun Anda, pastikan tidak ada tugas atau data operasional penting yang masih terikat dengan akun ini.
    </div>

    <div>
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow-sm shadow-rose-200 transition">
            <i class="fa-solid fa-trash-can"></i>
            Hapus Akun Saya
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 space-y-5">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 text-rose-600">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800">
                        Konfirmasi Penghapusan Akun
                    </h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-sm text-slate-600 leading-relaxed">
                Apakah Anda benar-benar yakin ingin menghapus akun Anda? Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan secara permanen.
            </p>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Kata Sandi Konfirmasi
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border @if($errors->userDeletion->has('password')) border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-100 outline-none transition"
                    placeholder="Masukkan kata sandi Anda"
                />
                @if ($errors->userDeletion->has('password'))
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition">
                    Batal
                </button>

                <button type="submit"
                    class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-6 py-2.5 rounded-2xl shadow-sm shadow-rose-200 transition">
                    <i class="fa-solid fa-trash-can"></i>
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
