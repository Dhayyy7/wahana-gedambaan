{{-- Modal Pengaturan Printer Bluetooth --}}
<div id="bluetooth-printer-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeBluetoothModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100 p-6">
            
            {{-- Header Modal --}}
            <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-brands fa-bluetooth-b"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800" id="modal-title">Printer Bluetooth</h3>
                        <p class="text-xs text-slate-500">Koneksi langsung printer termal 58mm/80mm</p>
                    </div>
                </div>
                <button type="button" onclick="closeBluetoothModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-50 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Insecure Context Warning (Jika dibuka via http bukan https atau localhost) --}}
            <div id="bt-insecure-warning" class="hidden my-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm mt-0.5 shrink-0"></i>
                    <div class="space-y-2">
                        <p class="font-semibold">Perhatian: Web Bluetooth Memerlukan HTTPS</p>
                        <p class="text-amber-700 leading-relaxed">
                            Browser memblokir fitur Bluetooth jika diakses lewat protokol HTTP biasa (<code class="bg-amber-100/70 px-1 py-0.5 rounded font-mono" id="current-host-label"></code>).
                            Pastikan website diakses menggunakan koneksi aman <strong>HTTPS</strong> (<code class="bg-amber-100/70 px-1 py-0.5 rounded font-mono">https://...</code>).
                        </p>
                        <div id="bt-switch-localhost-wrapper" class="hidden">
                            <button type="button" onclick="switchToLocalhost()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs shadow-xs transition cursor-pointer">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                <span>Buka via Localhost (Development)</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body Status --}}
            <div class="py-4 space-y-4">
                {{-- Status Box --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Status Koneksi</label>
                    <div id="bt-modal-status">
                        <div class="flex items-center gap-2.5 text-slate-600 bg-slate-50 border border-slate-200 px-4 py-3 rounded-xl text-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                            <span>Belum terhubung ke printer Bluetooth.</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-2 pt-2">
                    <button type="button" id="bt-btn-scan" onclick="handleScanBluetooth()"
                        class="w-full py-3 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-200 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Scan & Hubungkan Printer</span>
                    </button>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" id="bt-btn-test" onclick="handleTestPrint()"
                            class="py-2.5 px-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition flex items-center justify-center gap-1.5 opacity-50 cursor-not-allowed">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Tes Cetak</span>
                        </button>

                        <button type="button" id="bt-btn-disconnect" onclick="handleDisconnectBluetooth()" style="display: none;"
                            class="py-2.5 px-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-semibold text-xs transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-link-slash"></i>
                            <span>Putuskan</span>
                        </button>
                    </div>
                </div>

                {{-- Petunjuk Singkat --}}
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-xs text-slate-500 space-y-1.5">
                    <p class="font-bold text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-question text-indigo-500"></i>
                        Panduan Cepat:
                    </p>
                    <ol class="list-decimal list-inside space-y-1 text-slate-600 leading-relaxed pl-1">
                        <li>Pastikan Bluetooth di komputer/HP Anda sudah aktif.</li>
                        <li>Nyalakan printer termal Bluetooth Anda.</li>
                        <li>Klik <strong>"Scan & Hubungkan Printer"</strong>, lalu pilih nama printer di daftar pop-up browser.</li>
                    </ol>
                </div>
            </div>

            {{-- Footer --}}
            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closeBluetoothModal()"
                    class="py-2 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openBluetoothModal() {
        const modal = document.getElementById('bluetooth-printer-modal');
        if (modal) {
            modal.classList.remove('hidden');

            // Cek apakah secure context (HTTPS / Localhost)
            const warningEl = document.getElementById('bt-insecure-warning');
            const hostLabel = document.getElementById('current-host-label');
            if (hostLabel) hostLabel.innerText = window.location.host;

            if (warningEl) {
                if (!window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                    warningEl.classList.remove('hidden');
                    const isLocalDomain = window.location.hostname.endsWith('.test') || window.location.hostname.endsWith('.local') || window.location.hostname === '127.0.0.1';
                    const switchWrapper = document.getElementById('bt-switch-localhost-wrapper');
                    if (switchWrapper) {
                        switchWrapper.classList.toggle('hidden', !isLocalDomain);
                    }
                } else {
                    warningEl.classList.add('hidden');
                }
            }

            if (window.ThermalBluetooth) {
                window.ThermalBluetooth.updateUI();
            }
        }
    }

    function closeBluetoothModal() {
        const modal = document.getElementById('bluetooth-printer-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function switchToLocalhost() {
        const currentUrl = new URL(window.location.href);
        currentUrl.hostname = 'localhost';
        window.location.href = currentUrl.toString();
    }

    async function handleScanBluetooth() {
        if (!window.ThermalBluetooth) {
            alert('Modul Bluetooth belum siap.');
            return;
        }
        try {
            await window.ThermalBluetooth.connect();
        } catch (e) {
            // Error sudah ditangani di dalam ThermalBluetooth
        }
    }

    async function handleTestPrint() {
        if (window.ThermalBluetooth) {
            await window.ThermalBluetooth.printTest();
        }
    }

    function handleDisconnectBluetooth() {
        if (window.ThermalBluetooth) {
            window.ThermalBluetooth.disconnect();
        }
    }

    // Fungsi global untuk cetak tiket dari tombol baris tabel
    async function printSingleTicketBluetooth(buttonElement) {
        if (!window.ThermalBluetooth) return;

        const tiketDataStr = buttonElement.getAttribute('data-tiket');
        if (!tiketDataStr) return;

        try {
            const tiketData = JSON.parse(tiketDataStr);
            if (!window.ThermalBluetooth.isConnected()) {
                // Tampilkan modal agar kasir menghubungkan printer terlebih dahulu
                openBluetoothModal();
                window.ThermalBluetooth.showToast('Silakan hubungkan printer Bluetooth terlebih dahulu.', 'info');
                return;
            }

            await window.ThermalBluetooth.printTicket(tiketData);
        } catch (err) {
            console.error('Error parsing tiket data:', err);
            alert('Gagal memproses data tiket: ' + err.message);
        }
    }
</script>
