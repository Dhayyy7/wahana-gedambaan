<x-app-layout>
    <div class="space-y-6">

        <!-- ================= 1. WELCOME HERO BANNER ================= -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0058b8] via-[#0275d8] to-[#0ea5e9] text-white shadow-xl shadow-blue-900/10 min-h-[200px] flex items-center">
            <!-- Background Image Speedboat dengan Overlay Transparan -->
            <div class="absolute inset-0 bg-cover bg-right bg-no-repeat opacity-40 mix-blend-overlay pointer-events-none" style="background-image: url('{{ asset('images/speedboat-banner.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#004b9e] via-[#0267c1]/90 to-transparent"></div>

            <div class="relative z-10 px-8 py-8 sm:px-10 max-w-2xl">
                <span class="text-xs sm:text-sm font-medium text-sky-200 tracking-wide">
                    Selamat datang kembali,
                </span>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </h1>
                    <span class="text-2xl sm:text-3xl animate-bounce">🚤</span>
                </div>
                <p class="mt-2 text-xs sm:text-sm text-sky-100/90 leading-relaxed max-w-lg font-normal">
                    Kelola pemesanan, kapal, dan pelanggan dengan mudah di sistem SpeedBoat Rental.
                </p>
                <div class="mt-5">
                    <a
                        href="#jadwal"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-blue-600 font-semibold text-xs sm:text-sm shadow-md hover:bg-blue-50 active:scale-95 transition-all duration-200"
                    >
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Lihat Jadwal Hari Ini</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= 2. FOUR STAT CARDS ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            <!-- Card 1: Total Pemesanan -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l2-5h14l2 5M4 20a4 4 0 008 0 4 4 0 008 0"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Total Pemesanan</span>
                            <span class="text-2xl font-extrabold text-slate-900 mt-0.5 block">48</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2 border-t border-slate-50">
                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        18% <span class="text-slate-400 font-normal ml-0.5">dari minggu lalu</span>
                    </span>
                    <!-- Sparkline Mini Chart (Blue) -->
                    <svg class="w-16 h-7 text-sky-500 overflow-visible" viewBox="0 0 60 25" fill="none">
                        <path d="M2 20 Q 15 15, 25 18 T 45 8 T 58 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>

            <!-- Card 2: Pelanggan Aktif -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Pelanggan Aktif</span>
                            <span class="text-2xl font-extrabold text-slate-900 mt-0.5 block">32</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2 border-t border-slate-50">
                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        12% <span class="text-slate-400 font-normal ml-0.5">dari minggu lalu</span>
                    </span>
                    <!-- Sparkline Mini Chart (Green) -->
                    <svg class="w-16 h-7 text-emerald-500 overflow-visible" viewBox="0 0 60 25" fill="none">
                        <path d="M2 22 Q 15 18, 28 14 T 48 8 T 58 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>

            <!-- Card 3: Kapal Tersedia -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 20a6 6 0 0 0 10 0 6 6 0 0 0 10 0"/>
                                <path d="M4 17l2-5h12l2 5"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Kapal Tersedia</span>
                            <span class="text-2xl font-extrabold text-slate-900 mt-0.5 block">4</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2 border-t border-slate-50">
                    <span class="text-xs text-slate-500 font-medium">
                        Dari 5 total kapal
                    </span>
                    <!-- Sparkline Mini Chart (Purple) -->
                    <svg class="w-16 h-7 text-purple-500 overflow-visible" viewBox="0 0 60 25" fill="none">
                        <path d="M2 18 Q 15 10, 28 16 T 48 10 T 58 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>

            <!-- Card 4: Pendapatan -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 font-extrabold text-sm flex items-center justify-center shrink-0">
                            Rp
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 block">Pendapatan</span>
                            <span class="text-xl font-extrabold text-slate-900 mt-0.5 block">Rp 12.500.000</span>
                        </div>
                    </div>
                    <a href="#" class="text-[11px] font-bold text-sky-600 hover:text-sky-700 hover:underline flex items-center gap-0.5">
                        Lihat Semua &rarr;
                    </a>
                </div>
                <div class="flex items-center justify-between mt-4 pt-2 border-t border-slate-50">
                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        24% <span class="text-slate-400 font-normal ml-0.5">dari minggu lalu</span>
                    </span>
                    <!-- Sparkline Mini Chart (Amber) -->
                    <svg class="w-16 h-7 text-amber-500 overflow-visible" viewBox="0 0 60 25" fill="none">
                        <path d="M2 19 Q 15 12, 30 18 T 45 10 T 58 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>

        </div>

        <!-- ================= 3. CHARTS, STATUS & RECENT BOOKINGS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Left & Center: Chart & Fleet Status (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Tren Pemesanan 7 Hari Terakhir Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-blue-50 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-800">
                                Tren Pemesanan 7 Hari Terakhir
                            </h2>
                        </div>

                        <!-- Dropdown Filter: 7 Hari Terakhir -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer select-none">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>7 Hari Terakhir</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <!-- Line Chart SVG (Interactive & Smooth) -->
                    <div class="w-full h-56 relative">
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 700 200" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>

                            <!-- Horizontal Grid Lines -->
                            <line x1="40" y1="20" x2="680" y2="20" stroke="#f1f5f9" stroke-width="1.5"/>
                            <text x="15" y="24" fill="#94a3b8" font-size="11" font-weight="600">20</text>

                            <line x1="40" y1="65" x2="680" y2="65" stroke="#f1f5f9" stroke-width="1.5"/>
                            <text x="15" y="69" fill="#94a3b8" font-size="11" font-weight="600">15</text>

                            <line x1="40" y1="110" x2="680" y2="110" stroke="#f1f5f9" stroke-width="1.5"/>
                            <text x="15" y="114" fill="#94a3b8" font-size="11" font-weight="600">10</text>

                            <line x1="40" y1="155" x2="680" y2="155" stroke="#f1f5f9" stroke-width="1.5"/>
                            <text x="22" y="159" fill="#94a3b8" font-size="11" font-weight="600">5</text>

                            <line x1="40" y1="185" x2="680" y2="185" stroke="#e2e8f0" stroke-width="1.5"/>
                            <text x="22" y="189" fill="#94a3b8" font-size="11" font-weight="600">0</text>

                            <!-- Chart Area Path -->
                            <path d="M 50 145 Q 150 75, 250 120 T 450 90 T 550 95 T 670 50 L 670 185 L 50 185 Z" fill="url(#chartGradient)"/>

                            <!-- Main Spline Line -->
                            <path d="M 50 145 Q 150 75, 250 120 T 450 90 T 550 95 T 670 50" fill="none" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round"/>

                            <!-- Data Points (Circles) -->
                            <circle cx="50" cy="145" r="4.5" fill="#2563eb" stroke="#ffffff" stroke-width="2.5" class="transition hover:r-6"/>
                            <circle cx="150" cy="98" r="4.5" fill="#2563eb" stroke="#ffffff" stroke-width="2.5" class="transition hover:r-6"/>
                            <circle cx="250" cy="120" r="4.5" fill="#2563eb" stroke="#ffffff" stroke-width="2.5" class="transition hover:r-6"/>
                            <circle cx="350" cy="126" r="4.5" fill="#2563eb" stroke="#ffffff" stroke-width="2.5" class="transition hover:r-6"/>
                            <circle cx="450" cy="90" r="4.5" fill="#2563eb" stroke="#ffffff" stroke-width="2.5" class="transition hover:r-6"/>
                            <circle cx="550" cy="98" r="4.5" fill="#2563eb" stroke="#ffffff" stroke-width="2.5" class="transition hover:r-6"/>
                            <circle cx="670" cy="50" r="5" fill="#2563eb" stroke="#ffffff" stroke-width="3" class="transition hover:r-7"/>
                        </svg>

                        <!-- X Axis Labels -->
                        <div class="flex justify-between pl-8 pr-3 text-[11px] font-semibold text-slate-400 mt-2">
                            <span>20 Apr</span>
                            <span>21 Apr</span>
                            <span>22 Apr</span>
                            <span>23 Apr</span>
                            <span>24 Apr</span>
                            <span>25 Apr</span>
                            <span>26 Apr</span>
                        </div>
                    </div>
                </div>

                <!-- Status Pemesanan Row -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-sky-50 text-sky-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-800">
                                Status Pemesanan
                            </h2>
                        </div>
                        <a href="#" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <!-- 4 Progress Bars List -->
                    <div class="space-y-4">
                        <!-- Menunggu Konfirmasi -->
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold mb-1.5">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                    <span>Menunggu Konfirmasi</span>
                                </div>
                                <span class="font-bold text-slate-900">5</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: 25%;"></div>
                            </div>
                        </div>

                        <!-- Dikonfirmasi -->
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold mb-1.5">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span>Dikonfirmasi</span>
                                </div>
                                <span class="font-bold text-slate-900">12</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: 60%;"></div>
                            </div>
                        </div>

                        <!-- Dalam Perjalanan -->
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold mb-1.5">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                                    <span>Dalam Perjalanan</span>
                                </div>
                                <span class="font-bold text-slate-900">8</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-sky-500 rounded-full" style="width: 40%;"></div>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold mb-1.5">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                                    <span>Selesai</span>
                                </div>
                                <span class="font-bold text-slate-900">23</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 rounded-full" style="width: 90%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Card: Nikmati Keindahan Laut -->
                <div class="bg-gradient-to-r from-sky-50 via-blue-50 to-white rounded-3xl p-6 border border-sky-100/80 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l2-5h14l2 5M4 20a4 4 0 008 0 4 4 0 008 0"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">
                                Nikmati keindahan laut dengan armada terbaik kami.
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Perjalanan aman, nyaman, dan tak terlupakan.
                            </p>
                        </div>
                    </div>
                    <a
                        href="#"
                        class="px-5 py-2.5 bg-white hover:bg-slate-50 text-blue-600 font-semibold text-xs sm:text-sm rounded-full shadow-xs border border-blue-200 shrink-0 transition"
                    >
                        Lihat Semua Kapal &rarr;
                    </a>
                </div>

            </div>

            <!-- Right Column: Recent Bookings & Weather Widget (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Pemesanan Terbaru Card -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-800">
                                Pemesanan Terbaru
                            </h2>
                        </div>
                        <a href="#" class="text-xs font-bold text-sky-600 hover:text-sky-700 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <!-- List Items -->
                    <div class="space-y-4">
                        <!-- Item 1 -->
                        <div class="p-3.5 rounded-2xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ asset('images/speedboat-banner.jpg') }}" alt="Speed Boat 01" class="w-12 h-12 rounded-xl object-cover shrink-0">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-extrabold text-slate-800">SB-001234</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium truncate">Speed Boat 01</p>
                                    <p class="text-[10px] text-slate-400 truncate">Pantai Gedambaan &rarr; P. Sampu</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-md">
                                    Selesai
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">26 Apr 2025</p>
                                <p class="text-[10px] font-semibold text-slate-600">2 Orang</p>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="p-3.5 rounded-2xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ asset('images/speedboat-banner.jpg') }}" alt="Speed Boat 02" class="w-12 h-12 rounded-xl object-cover shrink-0">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-extrabold text-slate-800">SB-001233</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium truncate">Speed Boat 02</p>
                                    <p class="text-[10px] text-slate-400 truncate">Pantai Gedambaan &rarr; P. Kucing</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-block px-2 py-0.5 bg-sky-50 text-sky-600 text-[10px] font-bold rounded-md">
                                    Dalam Perjalanan
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">26 Apr 2025</p>
                                <p class="text-[10px] font-semibold text-slate-600">4 Orang</p>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="p-3.5 rounded-2xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ asset('images/speedboat-banner.jpg') }}" alt="Speed Boat 01" class="w-12 h-12 rounded-xl object-cover shrink-0">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-extrabold text-slate-800">SB-001232</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium truncate">Speed Boat 01</p>
                                    <p class="text-[10px] text-slate-400 truncate">Pantai Gedambaan &rarr; P. Sadranan</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-md">
                                    Menunggu
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">25 Apr 2025</p>
                                <p class="text-[10px] font-semibold text-slate-600">3 Orang</p>
                            </div>
                        </div>

                        <!-- Item 4 -->
                        <div class="p-3.5 rounded-2xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ asset('images/speedboat-banner.jpg') }}" alt="Speed Boat 03" class="w-12 h-12 rounded-xl object-cover shrink-0">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-extrabold text-slate-800">SB-001231</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium truncate">Speed Boat 03</p>
                                    <p class="text-[10px] text-slate-400 truncate">Pantai Gedambaan &rarr; Nusa Barong</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-md">
                                    Selesai
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">25 Apr 2025</p>
                                <p class="text-[10px] font-semibold text-slate-600">5 Orang</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cuaca Hari Ini Card -->
                <div class="bg-gradient-to-br from-amber-50/80 via-white to-sky-50/50 rounded-3xl p-5 shadow-sm border border-amber-100/60 relative overflow-hidden">
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center gap-3">
                            <!-- Weather Sun Icon -->
                            <div class="text-amber-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="4" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-2xl font-extrabold text-slate-900 leading-none block">31°C</span>
                                <span class="text-[11px] font-semibold text-slate-500 mt-0.5 block">Cerah Berawan</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] text-slate-400 font-medium block">Cuaca Hari Ini</span>
                            <span class="text-[11px] font-semibold text-sky-600 flex items-center gap-1 justify-end mt-0.5">
                                <svg class="w-3 h-3 text-sky-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Kotabaru, Kalsel
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
