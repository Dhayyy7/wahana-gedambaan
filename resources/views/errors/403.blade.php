<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Terbatas | Wahana Gedambaan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex items-center justify-center p-4 selection:bg-blue-500 selection:text-white">
    <div class="max-w-md w-full text-center">
        <!-- Card Container -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/60 border border-slate-100">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto rounded-3xl bg-rose-50 text-rose-500 flex items-center justify-center text-4xl shadow-sm mb-6">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <!-- Error Code & Title -->
            <span class="inline-block px-3 py-1 rounded-full text-xs font-black tracking-widest text-rose-600 bg-rose-50 border border-rose-200 mb-3 uppercase">
                Error 403 • Dilarang
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Akses Dibatasi
            </h1>
            <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                {{ $exception->getMessage() ?: 'Akun Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya diperuntukkan bagi peran tertentu.' }}
            </p>

            @auth
                <div class="mt-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 inline-flex items-center gap-2 text-xs text-slate-600">
                    <i class="fa-solid fa-user-tag text-blue-500"></i>
                    <span>Masuk sebagai: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role_label }})</span>
                </div>
            @endauth

            <!-- Back to Dashboard Button -->
            <div class="mt-8 flex flex-col gap-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-sky-600 text-white font-bold text-sm shadow-lg shadow-blue-500/25 hover:from-blue-700 hover:to-sky-700 active:scale-95 transition-all">
                    <i class="fa-solid fa-house"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Watermark -->
        <p class="mt-6 text-xs text-slate-400 font-medium">
            Wahana Gedambaan • Sistem Tiket & Penyeberangan
        </p>
    </div>
</body>
</html>
