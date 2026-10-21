<section>
    <header class="flex items-center gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-key"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Ubah Kata Sandi
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Pastikan akun Anda menggunakan kata sandi yang panjang dan aman.
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div x-data="{ showPass: false }">
            <label for="update_password_current_password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                Kata Sandi Saat Ini <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-sm">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input id="update_password_current_password" name="current_password"
                    :type="showPass ? 'text' : 'password'"
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi saat ini"
                    class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border @if($errors->updatePassword->has('current_password')) border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                <button type="button" @click="showPass = !showPass"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            @if ($errors->updatePassword->has('current_password'))
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- New Password --}}
            <div x-data="{ showPass: false }">
                <label for="update_password_password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Kata Sandi Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="update_password_password" name="password"
                        :type="showPass ? 'text' : 'password'"
                        autocomplete="new-password"
                        placeholder="Minimal 6 karakter"
                        class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border @if($errors->updatePassword->has('password')) border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    <button type="button" @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @if ($errors->updatePassword->has('password'))
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->updatePassword->first('password') }}
                    </p>
                @endif
            </div>

            {{-- Confirm Password --}}
            <div x-data="{ showPass: false }">
                <label for="update_password_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <input id="update_password_password_confirmation" name="password_confirmation"
                        :type="showPass ? 'text' : 'password'"
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi baru"
                        class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border @if($errors->updatePassword->has('password_confirmation')) border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                    <button type="button" @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @if ($errors->updatePassword->has('password_confirmation'))
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->updatePassword->first('password_confirmation') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Tombol Simpan & Status --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                <i class="fa-solid fa-shield-halved"></i>
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-2"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3.5 py-2 rounded-xl">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    Kata sandi berhasil diperbarui!
                </div>
            @endif
        </div>
    </form>
</section>
