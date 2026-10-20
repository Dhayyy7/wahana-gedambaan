<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Pengguna</h1>
                <p class="text-sm text-slate-500 mt-0.5">Memperbarui akun {{ $user->name }} ({{ $user->role_label }}).</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Card Pilihan Peran (Role) --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-id-badge text-blue-500 text-sm"></i>
                    Pilih Peran (Role) <span class="text-red-500">*</span>
                </h2>

                <div x-data="{ selectedRole: '{{ old('role', $user->role) }}' }" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    {{-- Kasir (Flag 2) --}}
                    <label for="role_kasir"
                        @click="selectedRole = '2'"
                        class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all duration-150 select-none"
                        :class="selectedRole == '2' ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-100 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                        <input type="radio" id="role_kasir" name="role" value="2"
                            x-model="selectedRole"
                            class="sr-only" />
                        <div class="absolute top-3.5 right-3.5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                            :class="selectedRole == '2' ? 'border-blue-600 bg-blue-600' : 'border-slate-300 bg-white'">
                            <i class="fa-solid fa-check text-[10px] text-white" x-show="selectedRole == '2'"></i>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-2.5">
                            <i class="fa-solid fa-cash-register"></i>
                        </div>
                        <p class="font-bold text-slate-800 text-sm">Kasir</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Flag 2</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Input pemesanan tiket, cetak struk kasir, dan lihat riwayat tiket.
                        </p>
                    </label>

                    {{-- Operator (Flag 3) --}}
                    <label for="role_operator"
                        @click="selectedRole = '3'"
                        class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all duration-150 select-none"
                        :class="selectedRole == '3' ? 'border-emerald-600 bg-emerald-50/60 ring-2 ring-emerald-100 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                        <input type="radio" id="role_operator" name="role" value="3"
                            x-model="selectedRole"
                            class="sr-only" />
                        <div class="absolute top-3.5 right-3.5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                            :class="selectedRole == '3' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300 bg-white'">
                            <i class="fa-solid fa-check text-[10px] text-white" x-show="selectedRole == '3'"></i>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mb-2.5">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <p class="font-bold text-slate-800 text-sm">Operator</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Flag 3</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Scan QR Code tiket di dermaga dan pantau kapal berlayar.
                        </p>
                    </label>

                    {{-- Superadmin (Flag 1) --}}
                    <label for="role_superadmin"
                        @click="selectedRole = '1'"
                        class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all duration-150 select-none"
                        :class="selectedRole == '1' ? 'border-purple-600 bg-purple-50/60 ring-2 ring-purple-100 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                        <input type="radio" id="role_superadmin" name="role" value="1"
                            x-model="selectedRole"
                            class="sr-only" />
                        <div class="absolute top-3.5 right-3.5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                            :class="selectedRole == '1' ? 'border-purple-600 bg-purple-600' : 'border-slate-300 bg-white'">
                            <i class="fa-solid fa-check text-[10px] text-white" x-show="selectedRole == '1'"></i>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-2.5">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <p class="font-bold text-slate-800 text-sm">Superadmin</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Flag 1</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Akses penuh ke seluruh menu, data kapal, tiket, dan manajemen user.
                        </p>
                    </label>
                </div>
                @error('role')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Card Informasi Pengguna --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-user text-blue-500 text-sm"></i>
                    Informasi Akun
                </h2>

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border @error('name') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
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
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-xs">
                                @
                            </span>
                            <input type="text" id="username" name="username"
                                value="{{ old('username', $user->username) }}"
                                class="w-full pl-8 pr-4 py-2.5 text-sm rounded-xl border @error('username') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition font-mono" />
                        </div>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </span>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border @error('email') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Ubah Password (Opsional) --}}
                <div class="pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-500 mb-3">
                        <i class="fa-solid fa-lock text-slate-400 mr-1"></i>
                        Kosongkan kolom password di bawah jika <strong>tidak ingin mengubah</strong> password saat ini.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Password Baru
                            </label>
                            <input type="password" id="password" name="password"
                                placeholder="Minimal 6 karakter"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border @error('password') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Ulangi Password Baru
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Ulangi password baru"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-7 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                    <i class="fa-solid fa-check"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
