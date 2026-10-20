<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola akun dan hak akses Superadmin, Kasir, dan Operator.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                <i class="fa-solid fa-user-plus"></i>
                Tambah User
            </a>
        </div>
    </x-slot>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div id="flash-success"
            class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-500 shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div id="flash-error"
            class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
            <span>{{ $errors->first() }}</span>
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-rose-400 hover:text-rose-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Stats Cards Per Role --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Users --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 text-lg shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalUsers }}</p>
                <p class="text-xs text-slate-500 font-medium">Total Akun</p>
            </div>
        </div>

        {{-- Superadmin (Flag 1) --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 text-lg shrink-0">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalSuperadmin }}</p>
                <p class="text-xs text-slate-500 font-medium">Superadmin (Flag 1)</p>
            </div>
        </div>

        {{-- Kasir (Flag 2) --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg shrink-0">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalKasir }}</p>
                <p class="text-xs text-slate-500 font-medium">Kasir (Flag 2)</p>
            </div>
        </div>

        {{-- Operator (Flag 3) --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg shrink-0">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalOperator }}</p>
                <p class="text-xs text-slate-500 font-medium">Operator (Flag 3)</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan nama, email, atau username..."
                    class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
            </div>

            <div class="w-full sm:w-48">
                <select name="role"
                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <option value="">Semua Peran</option>
                    <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Superadmin (1)</option>
                    <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Kasir (2)</option>
                    <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>Operator (3)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl transition"
                        title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        @if ($users->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-slate-400">
                <i class="fa-solid fa-users text-5xl mb-4 opacity-30"></i>
                <p class="font-semibold text-slate-500">Tidak ada pengguna ditemukan</p>
                <p class="text-sm mt-1">Coba sesuaikan kata kunci pencarian atau tambah user baru.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider">Pengguna</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3.5 text-center font-semibold text-slate-500 text-xs uppercase tracking-wider">Peran (Role)</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wider hidden md:table-cell">Terdaftar</th>
                            <th class="px-5 py-3.5 text-right font-semibold text-slate-500 text-xs uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                {{-- User Info --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $user->role === 1 ? '7e22ce' : ($user->role === 2 ? '0284c7' : '059669') }}&color=fff&bold=true"
                                            alt="{{ $user->name }}"
                                            class="w-10 h-10 rounded-full object-cover shrink-0 ring-2 ring-slate-100" />
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                                @if ($user->id === Auth::id())
                                                    <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full">Anda</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-400 font-mono">{{ $user->username ? '@'.$user->username : '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-5 py-4 text-slate-600 font-medium text-xs">
                                    {{ $user->email }}
                                </td>

                                {{-- Role Badge --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($user->role === 1)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="fa-solid fa-user-shield text-[11px]"></i>
                                            Superadmin
                                        </span>
                                    @elseif ($user->role === 2)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fa-solid fa-cash-register text-[11px]"></i>
                                            Kasir
                                        </span>
                                    @elseif ($user->role === 3)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-qrcode text-[11px]"></i>
                                            Operator
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-slate-50 text-slate-700 border border-slate-200">
                                            Staff
                                        </span>
                                    @endif
                                </td>

                                {{-- Terdaftar --}}
                                <td class="px-5 py-4 text-slate-400 text-xs hidden md:table-cell">
                                    {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                                            title="Edit User">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>

                                        @if ($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition"
                                                    title="Hapus User">
                                                    <i class="fa-solid fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
