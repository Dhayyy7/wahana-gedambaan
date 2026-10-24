<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tiket.index') }}"
                    class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Tiket</h1>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $tiket->kode_tiket }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 print:hidden">
                @if ($tiket->status === 0)
                    <form method="POST" action="{{ route('admin.tiket.cancel', $tiket) }}"
                        onsubmit="return confirmAction(event, {
                            title: 'Batalkan Pesanan Tiket?',
                            text: 'Status tiket {{ $tiket->kode_tiket }} akan diubah menjadi Dibatalkan. Transaksi ini tidak dapat dipulihkan.',
                            type: 'warning',
                            confirmText: 'Ya, Batalkan',
                            cancelText: 'Kembali'
                        })">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-sm font-semibold px-4 py-2.5 rounded-2xl transition"
                            title="Batalkan Tiket">
                            <i class="fa-solid fa-ban"></i>
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif
                @if ($tiket->status !== -1)
                    <a href="{{ route('admin.tiket.print', $tiket) }}" target="_blank"
                        class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-2xl transition"
                        title="Buka struk termal di tab baru">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        Buka Struk
                    </a>
                    <button type="button" onclick="printThermalTicket()"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-2xl shadow-sm shadow-blue-200 transition">
                        <i class="fa-solid fa-print"></i>
                        Cetak Tiket (58mm)
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- Flash --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs print:hidden">
            <i class="fa-solid fa-circle-check text-emerald-500 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium px-5 py-3.5 rounded-2xl mb-6 shadow-xs print:hidden">
            <i class="fa-solid fa-triangle-exclamation text-rose-500 shrink-0"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif



    <div class="max-w-xl mx-auto space-y-5">

        {{-- ============ TIKET CARD PREVIEW ============ --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

            {{-- Status Banner jika Tiket Dibatalkan --}}
            @if ($tiket->status === -1)
                <div class="bg-rose-50 border-b border-rose-200 px-6 py-3 text-center text-xs font-bold text-rose-700 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-ban text-rose-600"></i>
                    <span>Pesanan tiket ini telah DIBATALKAN (Status -1) dan tidak berlaku lagi.</span>
                </div>
            @endif

            {{-- Header Tiket --}}
            <div class="bg-gradient-to-r {{ $tiket->status === -1 ? 'from-slate-600 to-slate-700' : 'from-blue-600 to-blue-700' }} p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/asset_login/logo.png') }}" alt="Logo"
                            class="h-10 w-auto object-contain brightness-0 invert" />
                        <div>
                            <p class="font-extrabold text-lg leading-tight">Wahana Gedambaan</p>
                            <p class="{{ $tiket->status === -1 ? 'text-slate-300' : 'text-blue-200' }} text-xs">Speed Boat Adventure</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="{{ $tiket->status === -1 ? 'text-slate-300' : 'text-blue-200' }} text-xs uppercase tracking-wider">Kode Tiket</p>
                        <p class="font-mono font-extrabold text-lg text-white">{{ $tiket->kode_tiket }}</p>
                    </div>
                </div>
            </div>

            {{-- Separator bulat seperti tiket asli --}}
            <div class="flex items-center px-6">
                <div class="w-6 h-6 rounded-full bg-[#F4F7FB] -ml-9"></div>
                <div class="flex-1 border-t-2 border-dashed border-slate-200 mx-2"></div>
                <div class="w-6 h-6 rounded-full bg-[#F4F7FB] -mr-9"></div>
            </div>

            {{-- Body Tiket --}}
            <div class="p-6 space-y-6">

                {{-- Status Badge --}}
                @if ($tiket->status === -1)
                    <div class="flex justify-center">
                        <span class="inline-flex items-center gap-2 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold px-3 py-1.5 rounded-full">
                            <i class="fa-solid fa-ban text-rose-500"></i>
                            Dibatalkan oleh Kasir/Admin
                        </span>
                    </div>
                @elseif ($tiket->status === 1)
                    <div class="flex justify-center">
                        <span class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-full">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            Sudah Digunakan — {{ $tiket->tanggal_scan?->format('d M Y, H:i') }}
                        </span>
                    </div>
                @endif

                {{-- QR Code --}}
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-white p-3.5 rounded-2xl border-2 border-slate-100 shadow-xs inline-block">
                        <div class="w-40 h-40 flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                            {!! QrCode::size(160)->margin(0)->generate($tiket->qr_code) !!}
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-2 break-all text-center max-w-xs">{{ $tiket->qr_code }}</p>
                </div>

                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="bg-slate-50/70 p-3 rounded-2xl">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Pengunjung</p>
                        <p class="font-bold text-slate-800 text-sm">{{ $tiket->nama_pembeli }}</p>
                    </div>
                    <div class="bg-slate-50/70 p-3 rounded-2xl">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Nomor HP</p>
                        <p class="font-semibold text-slate-700 text-sm">{{ $tiket->no_hp }}</p>
                    </div>
                    @if ($tiket->alamat)
                    <div class="col-span-2 bg-slate-50/70 p-3 rounded-2xl">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Alamat</p>
                        <p class="font-semibold text-slate-700 text-sm">{{ $tiket->alamat }}</p>
                    </div>
                    @endif
                    <div class="bg-slate-50/70 p-3 rounded-2xl">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Kapal</p>
                        <p class="font-bold text-slate-800 text-sm">{{ $tiket->kapal->nama_kapal }}</p>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $tiket->kapal->kode_kapal }}</p>
                    </div>
                    <div class="bg-slate-50/70 p-3 rounded-2xl">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Jenis Hari</p>
                        <p class="font-semibold text-slate-700 text-sm">{{ $tiket->is_weekend ? '🌅 Weekend' : '📅 Hari Biasa' }}</p>
                    </div>
                    <div class="bg-slate-50/70 p-3 rounded-2xl">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Waktu Pemesanan</p>
                        <p class="font-semibold text-slate-700 text-sm">{{ $tiket->tanggal_pemesanan->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="bg-slate-50/70 p-3 rounded-2xl">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Kasir</p>
                        <p class="font-semibold text-slate-700 text-sm">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                {{-- Separator --}}
                <div class="border-t border-dashed border-slate-200"></div>

                {{-- Total Bayar --}}
                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Tarif ({{ $tiket->is_weekend ? 'Weekend' : 'Hari Biasa' }})</span>
                        <span class="font-semibold">Rp {{ number_format($tiket->tarif, 0, ',', '.') }}</span>
                    </div>
                    @if ($tiket->diskon > 0)
                    <div class="flex justify-between text-sm text-emerald-600">
                        <span>Diskon</span>
                        <span class="font-semibold">- Rp {{ number_format($tiket->diskon, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                        <span class="font-bold text-slate-800">Total Bayar</span>
                        <span class="font-extrabold text-blue-600 text-xl">Rp {{ number_format($tiket->total_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Petunjuk Cetak --}}
                <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-3.5 text-center">
                    <p class="text-xs text-blue-700 font-medium">
                        <i class="fa-solid fa-receipt mr-1"></i>
                        Format struk disesuaikan untuk <strong>Kertas Printer Termal 58mm (Roll 58x30)</strong>.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Script untuk cetak ke struk 58mm hanya saat tombol cetak ditekan --}}
    <script>
        function printThermalTicket() {
            let frame = document.getElementById('thermal-print-frame');
            if (!frame) {
                frame = document.createElement('iframe');
                frame.id = 'thermal-print-frame';
                frame.style.cssText = 'position: fixed; right: 0; bottom: 0; width: 0; height: 0; border: 0; visibility: hidden;';
                document.body.appendChild(frame);
            }

            frame.onload = function() {
                try {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                } catch (e) {
                    console.error('Iframe print error, fallback to popup:', e);
                    window.open('{{ route('admin.tiket.print', $tiket) }}', '_blank', 'width=420,height=600');
                }
            };

            frame.src = '{{ route('admin.tiket.print', $tiket) }}';
        }
    </script>
</x-app-layout>
