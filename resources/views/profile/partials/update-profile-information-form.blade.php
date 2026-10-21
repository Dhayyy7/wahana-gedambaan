<section>
    <header class="flex items-center gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-user-pen"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Informasi Profil
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Perbarui nama lengkap, username, dan alamat email akun Anda.
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        {{-- Nama Lengkap --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-sm">
                    <i class="fa-solid fa-user"></i>
                </span>
                <input id="name" name="name" type="text"
                    value="{{ old('name', $user->name) }}"
                    required autofocus autocomplete="name"
                    placeholder="Masukkan nama lengkap"
                    class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border @error('name') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
            </div>
            @error('name')
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Username --}}
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Username
                    <span class="font-normal text-slate-400 text-xs">(opsional)</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-sm font-bold">
                        @
                    </span>
                    <input id="username" name="username" type="text"
                        value="{{ old('username', $user->username) }}"
                        autocomplete="username"
                        placeholder="username_anda"
                        class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border @error('username') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition font-mono" />
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-sm">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email"
                        value="{{ old('email', $user->email) }}"
                        required autocomplete="email"
                        placeholder="email@example.com"
                        class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border @error('email') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Peran Akun (Readonly Info) --}}
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-600 text-sm">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Peran / Hak Akses</p>
                    <p class="text-sm font-bold text-slate-800">{{ $user->role_label }} (Flag {{ $user->role }})</p>
                </div>
            </div>
            <span class="text-[11px] text-slate-400 italic hidden sm:inline">Dikelola oleh Administrator</span>
        </div>

        {{-- Verification Prompt if unverified --}}
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-800 flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm mt-0.5 shrink-0"></i>
                <div class="flex-1">
                    <p class="font-semibold">Alamat email Anda belum diverifikasi.</p>
                    <button form="send-verification" class="text-blue-600 hover:text-blue-800 underline font-semibold mt-1">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-emerald-600 font-bold">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Tombol Simpan & Status --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                <i class="fa-solid fa-check"></i>
                Simpan Profil
            </button>

            @if (session('status') === 'profile-updated')
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
                    Profil berhasil disimpan!
                </div>
            @endif
        </div>
    </form>
</section>
