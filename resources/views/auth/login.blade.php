<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/asset_login/logo.png') }}">

    <title>Login - {{ config('app.name', 'Speedboat Gedambaan') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="antialiased min-h-screen relative overflow-x-hidden flex items-center justify-center selection:bg-blue-500 selection:text-white">

    <!-- Background Image: Menggunakan aset asli dari public/images/asset_login/asset1.png -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/asset_login/beach-bg.jpg') }}');">
        <!-- Soft gradient overlay untuk meningkatkan kontras teks agar selalu terbaca jelas -->
        <div class="absolute inset-0 bg-gradient-to-r from-sky-950/30 via-sky-950/10 to-transparent"></div>
    </div>

    <!-- Decorative Bottom Right Wave Curve -->
    <div class="fixed bottom-0 right-0 pointer-events-none w-full max-w-3xl overflow-hidden leading-none z-0">
        <svg viewBox="0 0 1200 400" preserveAspectRatio="none" class="w-full h-44 sm:h-64 lg:h-80" fill="none">
            <path d="M0,320 C320,380 640,240 940,300 C1080,330 1150,280 1200,260 L1200,400 L0,400 Z" fill="rgba(255, 255, 255, 0.4)"></path>
            <path d="M0,345 C350,300 720,375 1020,325 C1110,310 1160,330 1200,340 L1200,400 L0,400 Z" fill="rgba(224, 242, 254, 0.55)"></path>
            <path d="M0,370 C400,350 800,390 1200,360 L1200,400 L0,400 Z" fill="rgba(255, 255, 255, 0.75)"></path>
        </svg>
    </div>

    <!-- Main Content Container -->
    <main class="relative z-10 w-full max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-10 min-h-screen flex items-center">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">

            <!-- Left Column: Welcome Greeting & Official Logo -->
            <div class="lg:col-span-7 flex flex-col justify-center text-left py-4">
                <!-- Speedboat Gedambaan Logo -->
                <div class="mb-4">
                    <img
                        src="{{ asset('images/asset_login/logo.png') }}"
                        alt="Logo Speedboat Gedambaan"
                        class="h-20 sm:h-24 md:h-28 w-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-200"
                    />
                </div>

                <!-- Judul Selamat Datang dengan warna Navy senada dengan teks 'Speedboat' di logo -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#0B3060] tracking-tight drop-shadow-[0_2px_8px_rgba(255,255,255,0.7)]">
                    Selamat Datang
                </h1>

                <!-- Deskripsi -->
                <p class="mt-3 text-slate-700 text-sm sm:text-base max-w-md leading-relaxed font-semibold drop-shadow-[0_1px_4px_rgba(255,255,255,0.8)]">
                    Nikmati pengalaman terbaik bersama kami.<br class="hidden sm:inline"> Masuk untuk melanjutkan.
                </p>
            </div>

            <!-- Right Column: Login Card -->
            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                <div class="w-full max-w-[420px] bg-white/95 backdrop-blur-xl rounded-3xl p-8 sm:p-10 shadow-2xl shadow-sky-950/15 border border-white/80 transition-all duration-300">

                    <!-- Card Header -->
                    <div class="mb-6">
                        <!-- Tampilan Logo saat di layar HP (Mobile Only) -->
                        <div class="flex lg:hidden mb-4 justify-center">
                            <img src="{{ asset('images/asset_login/logo.png') }}" alt="Speedboat Gedambaan" class="h-14 w-auto object-contain">
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                            Login
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                            Masuk ke akun Anda untuk melanjutkan ke aplikasi.
                        </p>
                    </div>

                    <!-- Session Status Alert -->
                    @if (session('status'))
                        <div class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email or Username Input -->
                        <div>
                            <div class="relative flex items-center rounded-xl border @error('email') border-red-400 bg-red-50/20 @else border-slate-200 bg-white hover:border-slate-300 @enderror focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 transition shadow-xs">
                                <div class="absolute left-3.5 text-slate-400 pointer-events-none flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="text"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Email atau username"
                                    class="w-full py-3 pl-11 pr-4 text-sm text-slate-800 placeholder-slate-400 bg-transparent rounded-xl focus:outline-none"
                                />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-600 mt-1.5 pl-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Input with Eye Toggle -->
                        <div x-data="{ showPassword: false }">
                            <div class="relative flex items-center rounded-xl border @error('password') border-red-400 bg-red-50/20 @else border-slate-200 bg-white hover:border-slate-300 @enderror focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 transition shadow-xs">
                                <div class="absolute left-3.5 text-slate-400 pointer-events-none flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Password"
                                    class="w-full py-3 pl-11 pr-11 text-sm text-slate-800 placeholder-slate-400 bg-transparent rounded-xl focus:outline-none"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    aria-label="Tampilkan / Sembunyikan Password"
                                    class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition"
                                >
                                    <!-- Eye Open (when password is hidden) -->
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye Slash (when password is shown) -->
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-600 mt-1.5 pl-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password Row -->
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 transition cursor-pointer"
                                />
                                <span class="text-xs sm:text-sm text-slate-600 font-medium">
                                    Ingat saya
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline transition">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button: Masuk -> -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 transition duration-200 cursor-pointer"
                            >
                                <span>Masuk</span>
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </main>

</body>
</html>
