<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon (Logo Tab Browser) -->
    <link rel="icon" type="image/png" href="{{ asset('images/asset_login/logo.png') }}">

    <title>Admin Dashboard - {{ config('app.name', 'Speedboat Gedambaan') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Caveat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Caveat:wght@600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-handwriting {
            font-family: 'Caveat', cursive;
        }
    </style>
</head>

<body class="bg-[#F4F7FB] text-slate-800 antialiased selection:bg-blue-600 selection:text-white"
    x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">

        <!-- Backdrop for mobile sidebar -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs lg:hidden"></div>

        <!-- ================= SIDEBAR ================= -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-100/80 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:translate-x-0 shadow-sm">
            <div class="flex flex-col h-full justify-between">
                <div>
                    <!-- Brand Header / Logo & Title -->
                    <div class="h-20 flex items-center justify-between px-5 border-b border-slate-50">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group py-1 min-w-0">
                            <img src="{{ asset('images/asset_login/logo.png') }}" alt="SpeedBoat Gedambaan"
                                class="h-11 w-auto shrink-0 object-contain transition group-hover:scale-105 duration-200" />
                            <div class="flex flex-col min-w-0">
                                <span class="font-extrabold text-sm text-[#0F3B73] tracking-tight leading-snug">
                                    Admin Dashboard
                                </span>
                            </div>
                        </a>

                        <!-- Close Button on Mobile -->
                        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 p-1">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <!-- Navigation Menu Items (with Font Awesome Icons) -->
                    <nav class="p-4 space-y-1.5">
                        <!-- Dashboard (Active) -->
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3.5 px-4 py-3 text-sm font-semibold rounded-2xl transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-house w-5 text-center text-base"></i>
                            <span>Dashboard</span>
                        </a>
                        <!-- Kapal (Hanya Superadmin) -->
                        @if (Auth::user()->isSuperadmin())
                        <a href="{{ route('admin.kapal.index') }}"
                            class="flex items-center gap-3.5 px-4 py-3 text-sm font-medium rounded-2xl transition duration-150 {{ request()->routeIs('admin.kapal.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-ship w-5 text-center text-base"></i>
                            <span>Kapal</span>
                        </a>
                        @endif

                        <!-- Pemesanan (Superadmin & Kasir) -->
                        @if (Auth::user()->isSuperadmin() || Auth::user()->isKasir())
                        <a href="{{ route('admin.tiket.index') }}"
                            class="flex items-center gap-3.5 px-4 py-3 text-sm font-medium rounded-2xl transition duration-150 {{ request()->routeIs('admin.tiket.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-calendar-days w-5 text-center text-base"></i>
                            <span>Pemesanan</span>
                        </a>
                        @endif

                        <!-- Scan Tiket (Superadmin & Operator) -->
                        @if (Auth::user()->isSuperadmin() || Auth::user()->isOperator())
                        <a href="{{ route('admin.scan.index') }}"
                            class="flex items-center gap-3.5 px-4 py-3 text-sm font-medium rounded-2xl transition duration-150 {{ request()->routeIs('admin.scan.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-qrcode w-5 text-center text-base"></i>
                            <span>Scan Tiket</span>
                        </a>
                        @endif

                        <!-- Users (Hanya Superadmin) -->
                        @if (Auth::user()->isSuperadmin())
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3.5 px-4 py-3 text-sm font-medium rounded-2xl transition duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-users-gear w-5 text-center text-base"></i>
                            <span>Users</span>
                        </a>
                        @endif

                        <!-- Laporan (Superadmin & Kasir) -->
                        @if (Auth::user()->isSuperadmin() || Auth::user()->isKasir())
                        <a href="{{ route('admin.laporan.index') }}"
                            class="flex items-center gap-3.5 px-4 py-3 text-sm font-medium rounded-2xl {{ request()->routeIs('admin.laporan.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }} transition duration-150">
                            <i class="fa-solid fa-chart-line w-5 text-center text-base"></i>
                            <span>Laporan</span>
                        </a>
                        @endif

                        <!-- Pengaturan -->
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3.5 px-4 py-3 text-sm font-medium rounded-2xl {{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }} transition duration-150">
                            <i class="fa-solid fa-gear w-5 text-center text-base"></i>
                            <span>Pengaturan</span>
                        </a>
                    </nav>
                </div>

                <!-- Bottom Watermark Illustration & Tagline -->
                <div class="p-6 relative overflow-hidden pointer-events-none select-none">
                    <div class="text-center relative z-10">
                        <p class="font-handwriting text-xl text-sky-700/80 transform -rotate-6 font-bold leading-tight">
                            Jelajahi Laut<br>Raih Cerita Baru
                        </p>
                        <div class="flex justify-center text-sky-400 mt-1">
                            <svg class="w-8 h-2" viewBox="0 0 40 8" fill="none">
                                <path d="M1 4C6 1 14 1 20 4C26 7 34 7 39 4" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>

                    <!-- Decorative Tropical Palm & Boat Silhouette -->
                    <div class="opacity-15 absolute -bottom-2 -left-2 text-sky-900 pointer-events-none">
                        <svg class="w-32 h-32" viewBox="0 0 100 100" fill="currentColor">
                            <path d="M10 90 Q 25 70 30 50 Q 32 30 25 15 Q 35 25 35 45 Q 35 70 50 90 Z" />
                            <path d="M25 15 Q 40 10 55 18 Q 40 22 30 25 Z" />
                            <path d="M20 25 Q 5 20 0 28 Q 10 30 18 32 Z" />
                            <path d="M50 85 L85 85 L90 80 L65 80 Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN LAYOUT WRAPPER ================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- ================= TOP NAVIGATION BAR ================= -->
            <header
                class="h-20 bg-white border-b border-slate-100/80 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">

                <!-- Left: Hamburger on Mobile & Search Bar -->
                <div class="flex items-center gap-4 flex-1">
                    <!-- Hamburger button for mobile -->
                    <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition"
                        aria-label="Buka Menu">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Search Input (Khusus Superadmin & Kasir) -->
                    @if (Auth::user()->isSuperadmin() || Auth::user()->isKasir())
                    <form method="GET" action="{{ route('admin.tiket.index') }}" class="relative w-full max-w-xs sm:max-w-sm lg:max-w-md">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pemesanan, pelanggan, atau kapal..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-xs sm:text-sm text-slate-700 placeholder-slate-400 rounded-2xl border border-slate-200/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition outline-none" />
                    </form>
                    @endif
                </div>

                <!-- Right: Notifications & Profile -->
                <div class="flex items-center gap-3 sm:gap-5">

                    <!-- Notification Bell Icon -->
                    <button type="button"
                        class="p-2.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl relative transition"
                        aria-label="Notifikasi">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <!-- Red Notification Dot Indicator -->
                        <span
                            class="w-2.5 h-2.5 bg-red-500 rounded-full absolute top-2 right-2 ring-2 ring-white"></span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ profileDropdown: false }">
                        <button @click="profileDropdown = !profileDropdown"
                            class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition cursor-pointer select-none"
                            aria-expanded="false">
                            <!-- Avatar -->
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=0284c7&color=fff&bold=true"
                                alt="{{ Auth::user()->name ?? 'Admin' }}"
                                class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-100" />

                            <!-- Name & Role (Hidden on very small mobile) -->
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-sm font-bold text-slate-800 leading-tight">
                                    {{ Auth::user()->name ?? 'Admin' }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium leading-tight mt-0.5">
                                    {{ Auth::user()->role_label ?? 'Staff' }}
                                </span>
                            </div>

                            <!-- Dropdown Arrow -->
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                                :class="{ 'rotate-180': profileDropdown }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="profileDropdown" x-cloak @click.away="profileDropdown = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl shadow-slate-900/10 border border-slate-100 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-slate-50 sm:hidden">
                                <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-50 transition">
                                <i class="fa-regular fa-user text-slate-400 text-sm w-4"></i>
                                <span>Profil Saya</span>
                            </a>

                            <div class="border-t border-slate-50 my-1"></div>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition text-left cursor-pointer">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-red-500 text-sm w-4"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <!-- ================= MAIN CONTENT AREA ================= -->
            <main class="flex-1 p-6 sm:p-8 overflow-y-auto">
                @isset($header)
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>

        </div>

    </div>

    {{-- ================= GLOBAL CONFIRMATION MODAL (MENGGANTIKAN WINDOW.CONFIRM BROWSER) ================= --}}
    <div id="global-confirm-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="window.closeGlobalConfirmModal()"></div>

            {{-- Modal Panel --}}
            <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 text-center overflow-hidden transform transition-all animate-in fade-in zoom-in-95 duration-200">
                {{-- Dynamic Icon Box --}}
                <div id="global-modal-icon-box" class="w-16 h-16 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4 border shadow-2xs">
                    <i id="global-modal-icon" class="fa-solid"></i>
                </div>

                {{-- Dynamic Title --}}
                <h3 id="global-modal-title" class="text-xl font-extrabold text-slate-800 tracking-tight"></h3>

                {{-- Dynamic Text --}}
                <p id="global-modal-text" class="text-sm text-slate-500 mt-2 leading-relaxed"></p>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 mt-6">
                    <button type="button" onclick="window.closeGlobalConfirmModal()" id="global-modal-cancel-btn"
                        class="w-1/2 py-2.5 px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition">
                        Batal
                    </button>
                    <button type="button" id="global-modal-confirm-btn"
                        class="w-1/2 py-2.5 px-4 rounded-2xl font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                        <span>Konfirmasi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let globalConfirmCallback = null;

        window.confirmAction = function(event, options = {}) {
            let form = null;
            if (event) {
                if (event.target) {
                    form = event.target.tagName === 'FORM' ? event.target : event.target.closest('form');
                }
                if (!form && event.currentTarget) {
                    form = event.currentTarget.tagName === 'FORM' ? event.currentTarget : event.currentTarget.closest('form');
                }
            }

            if (form && form.dataset && form.dataset.confirmed === 'true') {
                return true;
            }

            if (event) {
                if (typeof event.preventDefault === 'function') {
                    event.preventDefault();
                }
                if (typeof event.stopPropagation === 'function') {
                    event.stopPropagation();
                }
            }

            const title = options.title || 'Konfirmasi Tindakan';
            const text = options.text || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            const type = options.type || 'warning'; // warning, danger, info
            const confirmText = options.confirmText || 'Ya, Lanjutkan';
            const cancelText = options.cancelText || 'Batal';

            window.openGlobalConfirmModal({
                title,
                text,
                type,
                confirmText,
                cancelText,
                onConfirm: function() {
                    if (form && (form.tagName === 'FORM' || form instanceof HTMLFormElement)) {
                        form.dataset.confirmed = 'true';
                        HTMLFormElement.prototype.submit.call(form);
                    } else if (typeof options.onConfirm === 'function') {
                        options.onConfirm();
                    }
                }
            });

            return false;
        };

        window.openGlobalConfirmModal = function(options) {
            const modal = document.getElementById('global-confirm-modal');
            const iconBox = document.getElementById('global-modal-icon-box');
            const icon = document.getElementById('global-modal-icon');
            const titleEl = document.getElementById('global-modal-title');
            const textEl = document.getElementById('global-modal-text');
            const confirmBtn = document.getElementById('global-modal-confirm-btn');
            const cancelBtn = document.getElementById('global-modal-cancel-btn');

            titleEl.textContent = options.title;
            textEl.textContent = options.text;
            confirmBtn.querySelector('span').textContent = options.confirmText || 'Konfirmasi';
            cancelBtn.textContent = options.cancelText || 'Batal';

            const type = options.type || 'warning';
            if (type === 'danger') {
                iconBox.className = 'w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-3xl mx-auto mb-4 shadow-2xs';
                icon.className = 'fa-solid fa-triangle-exclamation';
                confirmBtn.className = 'w-1/2 py-2.5 px-4 rounded-2xl bg-rose-600 hover:bg-rose-700 active:scale-98 text-white font-bold text-sm shadow-md shadow-rose-200 transition flex items-center justify-center gap-2 cursor-pointer';
            } else if (type === 'warning') {
                iconBox.className = 'w-16 h-16 rounded-3xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-3xl mx-auto mb-4 shadow-2xs';
                icon.className = 'fa-solid fa-circle-exclamation';
                confirmBtn.className = 'w-1/2 py-2.5 px-4 rounded-2xl bg-amber-600 hover:bg-amber-700 active:scale-98 text-white font-bold text-sm shadow-md shadow-amber-200 transition flex items-center justify-center gap-2 cursor-pointer';
            } else {
                iconBox.className = 'w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-3xl mx-auto mb-4 shadow-2xs';
                icon.className = 'fa-solid fa-circle-question';
                confirmBtn.className = 'w-1/2 py-2.5 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-98 text-white font-bold text-sm shadow-md shadow-blue-200 transition flex items-center justify-center gap-2 cursor-pointer';
            }

            globalConfirmCallback = options.onConfirm;
            confirmBtn.onclick = function() {
                const callback = globalConfirmCallback;
                window.closeGlobalConfirmModal();
                if (typeof callback === 'function') {
                    callback();
                }
            };

            modal.classList.remove('hidden');
        };

        window.closeGlobalConfirmModal = function() {
            const modal = document.getElementById('global-confirm-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
            globalConfirmCallback = null;
        };
    </script>
</body>

</html>
