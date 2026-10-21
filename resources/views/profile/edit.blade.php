<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pengaturan Akun</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola informasi profil, preferensi, dan keamanan akun Anda.</p>
            </div>
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl text-xs font-bold border {{ $user->role_badge_class }}">
                    <i class="fa-solid {{ $user->role === 1 ? 'fa-user-shield' : ($user->role === 2 ? 'fa-cash-register' : 'fa-qrcode') }}"></i>
                    {{ $user->role_label }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Profile Hero Card --}}
        <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-sky-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-blue-200/50 relative overflow-hidden">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4 sm:gap-5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color={{ $user->role === 1 ? '7e22ce' : ($user->role === 2 ? '0284c7' : '059669') }}&bold=true&size=128"
                        alt="{{ $user->name }}"
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover shadow-md border-2 border-white/30 shrink-0" />
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-xl sm:text-2xl font-extrabold text-white leading-tight">{{ $user->name }}</h2>
                            <span class="bg-white/20 text-white text-[11px] font-bold px-2.5 py-0.5 rounded-full backdrop-blur-xs">
                                {{ $user->role_label }}
                            </span>
                        </div>
                        <p class="text-blue-100 text-xs sm:text-sm font-mono mt-1">
                            {{ $user->username ? '@'.$user->username : $user->email }}
                        </p>
                        <p class="text-blue-200/90 text-xs mt-1.5 flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar-check text-[11px]"></i>
                            Terdaftar sejak {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                        </p>
                    </div>
                </div>

                {{-- Status Akun Badge --}}
                <div class="flex items-center gap-2 self-start sm:self-auto bg-white/10 px-4 py-2 rounded-2xl backdrop-blur-xs border border-white/15">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-white tracking-wide">Akun Aktif</span>
                </div>
            </div>

            {{-- Decorative circles --}}
            <div class="absolute -right-8 -bottom-10 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute right-32 -top-12 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
        </div>

        {{-- Form 1: Informasi Profil --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Form 2: Ubah Kata Sandi --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs p-6 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Form 3: Zona Bahaya / Hapus Akun --}}
        @if ($user->role !== \App\Models\User::ROLE_SUPERADMIN)
            <div class="bg-white rounded-3xl border border-rose-100 shadow-xs p-6 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        @else
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs p-6 flex items-center gap-3.5 text-slate-500 text-xs">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-700">Akun Superadmin Dilindungi</p>
                    <p class="text-slate-400 mt-0.5">Akun administrator utama tidak dapat dihapus melalui menu pengaturan akun demi menjaga keutuhan sistem.</p>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
