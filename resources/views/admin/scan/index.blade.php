<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Scanner Tiket Wahana</h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Operator Ready
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-0.5">Pindai QR Code tiket pengunjung sebelum menaiki speed boat.</p>
            </div>

            {{-- Stat Singkat --}}
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <div class="bg-white border border-slate-100 rounded-2xl px-4 py-2 shadow-xs flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Tiket Scan Hari Ini</p>
                        <p class="text-base font-extrabold text-slate-800" id="stat-scan-hari-ini">{{ $totalScanHariIni }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Include HTML5-QRCode via CDN --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ================= KOLOM KIRI: SCANNER & INPUT (7 COLS) ================= --}}
        <div class="lg:col-span-7 space-y-5">

            {{-- Card Scanner Kamera --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs p-5 sm:p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Pemindai Kamera</h2>
                            <p class="text-xs text-slate-400">Arahkan kamera ke QR Code di tiket pengunjung</p>
                        </div>
                    </div>

                    {{-- Tombol Kontrol Kamera --}}
                    <div class="flex items-center gap-2">
                        <button id="btn-toggle-camera" type="button" onclick="toggleCamera()"
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition">
                            <i class="fa-solid fa-video text-xs" id="icon-camera-btn"></i>
                            <span id="text-camera-btn">Mulai Kamera</span>
                        </button>
                        <button id="btn-flip-camera" type="button" onclick="switchCamera()"
                            class="hidden p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-xl transition"
                            title="Ganti Kamera">
                            <i class="fa-solid fa-arrows-rotate text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Viewport Kamera --}}
                <div class="relative bg-slate-900 rounded-2xl overflow-hidden min-h-[300px] flex items-center justify-center">
                    {{-- Reader element --}}
                    <div id="qr-reader" class="w-full h-full"></div>

                    {{-- Placeholder saat kamera mati --}}
                    <div id="camera-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-slate-900 text-slate-400">
                        <div class="w-16 h-16 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-500 mb-3">
                            <i class="fa-solid fa-qrcode text-3xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-200">Kamera Belum Aktif</p>
                        <p class="text-xs text-slate-400 max-w-xs mt-1">
                            Klik tombol <strong>"Mulai Kamera"</strong> di atas untuk mengaktifkan pemindai, atau gunakan kolom ketik/scanner di bawah.
                        </p>
                    </div>

                    {{-- Scanner Overlay Animasi (saat kamera jalan) --}}
                    <div id="scanner-laser" class="hidden absolute inset-x-8 top-1/2 h-0.5 bg-blue-400 shadow-[0_0_15px_#38bdf8] animate-pulse pointer-events-none"></div>
                </div>
            </div>

            {{-- Card Input Manual / Barcode Scanner Fisik --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs p-5 sm:p-6">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Input Manual / Scanner USB</h2>
                        <p class="text-xs text-slate-400">Tempel barcode scanner hardware atau ketik kode tiket (misal: TKT-20261018-0001)</p>
                    </div>
                </div>

                <form id="form-manual-scan" onsubmit="handleManualSubmit(event)" class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-keyboard text-sm"></i>
                        </span>
                        <input type="text" id="manual-qr-input"
                            placeholder="Ketik kode tiket atau scan payload..."
                            autocomplete="off"
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition font-mono uppercase" />
                    </div>
                    <button type="submit" id="btn-manual-submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-xs transition flex items-center gap-2 shrink-0">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Verifikasi</span>
                    </button>
                </form>
            </div>

            {{-- Hasil Scan (Alert / Modal Card Dinamis) --}}
            <div id="scan-result-container" class="hidden"></div>

        </div>

        {{-- ================= KOLOM KANAN: STATUS KAPAL & LOG (5 COLS) ================= --}}
        <div class="lg:col-span-5 space-y-5">

            {{-- Card Kapal Sedang Berlayar --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs p-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <i class="fa-solid fa-ship"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Kapal Sedang Berlayar</h2>
                            <p class="text-xs text-slate-400">Unit kapal terkunci selama berlayar (trip ~30 menit)</p>
                        </div>
                    </div>
                    <span id="badge-kapal-berlayar" class="text-xs font-bold px-2.5 py-1 rounded-full {{ $kapalBerlayar->count() > 0 ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $kapalBerlayar->count() }} Kapal
                    </span>
                </div>

                <div id="list-kapal-berlayar" class="space-y-3">
                    @forelse ($kapalBerlayar as $kapal)
                        @php
                            $mulai = $kapal->waktu_mulai_trip;
                            $estimasi = $mulai ? $mulai->copy()->addMinutes(30) : null;
                            $sisaMenit = $estimasi ? max(0, now()->diffInMinutes($estimasi, false)) : 0;
                        @endphp
                        <div class="kapal-item border border-sky-100 bg-gradient-to-r from-sky-50/50 to-blue-50/30 rounded-2xl p-4 transition" id="kapal-card-{{ $kapal->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
                                        <h3 class="font-extrabold text-slate-800 text-sm">{{ $kapal->nama_kapal }}</h3>
                                        <span class="font-mono text-[11px] text-slate-400 bg-white px-2 py-0.5 rounded-md border border-slate-100">{{ $kapal->kode_kapal }}</span>
                                    </div>
                                    <div class="mt-2 text-xs text-slate-600 space-y-1">
                                        <p><i class="fa-regular fa-clock text-slate-400 w-4"></i> Mulai: <strong>{{ $mulai ? $mulai->format('H:i') : '-' }} WIB</strong></p>
                                        <p><i class="fa-solid fa-hourglass-half text-sky-500 w-4"></i> Estimasi Selesai: <strong>{{ $estimasi ? $estimasi->format('H:i') : '-' }} WIB</strong></p>
                                    </div>
                                </div>

                                {{-- Tombol Selesai Manual --}}
                                <button type="button" onclick="selesaiTrip({{ $kapal->id }}, '{{ $kapal->nama_kapal }}')"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-white hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 shadow-2xs transition shrink-0"
                                    title="Klik jika kapal telah kembali bersandar di dermaga sebelum 30 menit">
                                    <i class="fa-solid fa-anchor mr-1 text-emerald-600"></i> Selesai
                                </button>
                            </div>

                            {{-- Progress Bar 30 Menit --}}
                            <div class="mt-3 pt-2 border-t border-sky-100/60">
                                <div class="flex justify-between text-[11px] text-slate-400 mb-1">
                                    <span>Status: Berlayar</span>
                                    <span class="font-semibold text-sky-700">{{ $sisaMenit }} menit tersisa</span>
                                </div>
                                <div class="w-full bg-sky-200/60 rounded-full h-1.5 overflow-hidden">
                                    @php
                                        $persen = $mulai ? min(100, max(5, (now()->diffInSeconds($mulai) / (30 * 60)) * 100)) : 0;
                                    @endphp
                                    <div class="bg-sky-600 h-1.5 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                            <i class="fa-solid fa-sailboat text-3xl mb-2 opacity-30"></i>
                            <p class="text-xs font-semibold text-slate-500">Tidak ada kapal yang sedang berlayar</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Semua kapal yang aktif siap melayani penumpang.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Card Log Scan Terbaru Hari Ini --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs p-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Riwayat Scan Terkini</h2>
                            <p class="text-xs text-slate-400">Aktivitas scan yang berhasil hari ini</p>
                        </div>
                    </div>
                </div>

                <div id="live-scan-log" class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                    @forelse ($scanHariIni as $tiket)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50/70 border border-slate-100 text-xs">
                            <div class="min-w-0 pr-2">
                                <p class="font-bold text-slate-800 truncate">{{ $tiket->nama_pembeli }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $tiket->kode_tiket }} • {{ $tiket->kapal->nama_kapal }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-semibold text-slate-600">{{ $tiket->tanggal_scan ? $tiket->tanggal_scan->format('H:i:s') : '-' }}</span>
                                <p class="text-[10px] text-emerald-600 font-medium">Berhasil</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 bg-slate-50/40 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-xs">Belum ada aktivitas scan hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- ================= JAVASCRIPT SCANNER LOGIC ================= --}}
    <script>
        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const processUrl = "{{ route('admin.scan.process') }}";
        const selesaiTripUrlBase = "{{ url('admin/scan/selesai-trip') }}";

        let html5QrCode = null;
        let isCameraRunning = false;
        let currentCameraId = null;
        let cameraList = [];
        let currentCameraIndex = 0;
        let isProcessing = false; // Debounce flag agar tidak scan berkali-kali saat frame kamera jalan

        // ================= AUDIO SYNTHESIZER (WEB AUDIO API) =================
        // Menghasilkan bunyi Beep (Sukses) dan Buzzer (Gagal) tanpa file eksternal
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        let audioCtx = null;

        function initAudio() {
            if (!audioCtx) {
                audioCtx = new AudioContext();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
        }

        function playSuccessBeep() {
            try {
                initAudio();
                const now = audioCtx.currentTime;
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();

                osc.type = 'sine';
                // Frekuensi naik: nada gembira C5 -> G5
                osc.frequency.setValueAtTime(523.25, now);
                osc.frequency.exponentialRampToValueAtTime(783.99, now + 0.12);

                gain.gain.setValueAtTime(0.25, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.25);

                osc.connect(gain);
                gain.connect(audioCtx.destination);

                osc.start(now);
                osc.stop(now + 0.25);
            } catch (e) {
                console.log('Audio play error:', e);
            }
        }

        function playErrorBuzzer() {
            try {
                initAudio();
                const now = audioCtx.currentTime;
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();

                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(160, now);
                osc.frequency.linearRampToValueAtTime(110, now + 0.25);

                gain.gain.setValueAtTime(0.3, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.3);

                osc.connect(gain);
                gain.connect(audioCtx.destination);

                osc.start(now);
                osc.stop(now + 0.3);
            } catch (e) {
                console.log('Audio play error:', e);
            }
        }

        // ================= CAMERA CONTROLS =================
        async function toggleCamera() {
            initAudio();
            if (isCameraRunning) {
                stopCamera();
            } else {
                startCamera();
            }
        }

        async function startCamera() {
            const placeholder = document.getElementById('camera-placeholder');
            const laser = document.getElementById('scanner-laser');
            const btnText = document.getElementById('text-camera-btn');
            const btnIcon = document.getElementById('icon-camera-btn');
            const btnFlip = document.getElementById('btn-flip-camera');

            try {
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("qr-reader");
                }

                cameraList = await Html5Qrcode.getCameras();
                if (!cameraList || cameraList.length === 0) {
                    alert('Tidak ada kamera yang terdeteksi di perangkat ini.');
                    return;
                }

                // Default prioritaskan kamera belakang (environment) jika ada
                let selectedId = cameraList[0].id;
                for (let i = 0; i < cameraList.length; i++) {
                    const label = cameraList[i].label.toLowerCase();
                    if (label.includes('back') || label.includes('rear') || label.includes('belakang') || label.includes('environment')) {
                        selectedId = cameraList[i].id;
                        currentCameraIndex = i;
                        break;
                    }
                }

                currentCameraId = selectedId;

                const config = {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                };

                await html5QrCode.start(
                    currentCameraId,
                    config,
                    (decodedText, decodedResult) => {
                        handleQrScanned(decodedText);
                    },
                    (errorMessage) => {
                        // Frame scanning (ignore continuous read misses)
                    }
                );

                isCameraRunning = true;
                placeholder.classList.add('hidden');
                laser.classList.remove('hidden');
                btnText.textContent = 'Hentikan Kamera';
                btnIcon.className = 'fa-solid fa-stop text-xs';

                if (cameraList.length > 1) {
                    btnFlip.classList.remove('hidden');
                }

            } catch (err) {
                console.error("Camera start error:", err);
                alert("Gagal mengakses kamera: " + (err.message || err));
                stopCamera();
            }
        }

        async function stopCamera() {
            const placeholder = document.getElementById('camera-placeholder');
            const laser = document.getElementById('scanner-laser');
            const btnText = document.getElementById('text-camera-btn');
            const btnIcon = document.getElementById('icon-camera-btn');
            const btnFlip = document.getElementById('btn-flip-camera');

            if (html5QrCode && isCameraRunning) {
                try {
                    await html5QrCode.stop();
                } catch (e) {
                    console.error("Camera stop error:", e);
                }
            }

            isCameraRunning = false;
            placeholder.classList.remove('hidden');
            laser.classList.add('hidden');
            btnText.textContent = 'Mulai Kamera';
            btnIcon.className = 'fa-solid fa-video text-xs';
            btnFlip.classList.add('hidden');
        }

        async function switchCamera() {
            if (!isCameraRunning || cameraList.length <= 1) return;

            currentCameraIndex = (currentCameraIndex + 1) % cameraList.length;
            currentCameraId = cameraList[currentCameraIndex].id;

            await stopCamera();
            await startCamera();
        }

        // ================= SCAN PROCESSING LOGIC =================
        function handleQrScanned(qrCode) {
            if (isProcessing) return; // Prevent spamming duplicate frames
            isProcessing = true;

            // Pause camera frame visual if supported
            sendScanRequest(qrCode);
        }

        function handleManualSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('manual-qr-input');
            const code = input.value.trim();
            if (!code) return;

            sendScanRequest(code);
            input.value = '';
        }

        async function sendScanRequest(qrCode) {
            const resultContainer = document.getElementById('scan-result-container');
            resultContainer.innerHTML = `
                <div class="p-5 bg-white rounded-3xl border border-blue-100 shadow-sm flex items-center justify-center gap-3 text-blue-600">
                    <i class="fa-solid fa-circle-notch fa-spin text-xl"></i>
                    <span class="text-sm font-semibold">Memverifikasi tiket...</span>
                </div>
            `;
            resultContainer.classList.remove('hidden');

            try {
                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ qr_code: qrCode }),
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // SUKSES
                    playSuccessBeep();
                    renderSuccessResult(data.data, data.message);
                    updateStats();
                } else {
                    // GAGAL
                    playErrorBuzzer();
                    renderErrorResult(data);
                }

            } catch (err) {
                console.error("Network or server error:", err);
                playErrorBuzzer();
                renderErrorResult({
                    message: "Terjadi kesalahan jaringan atau server tidak merespons. Periksa koneksi internet.",
                });
            } finally {
                // Beri jeda 2.5 detik sebelum scanner kamera siap memindai QR code berikutnya
                setTimeout(() => {
                    isProcessing = false;
                }, 2500);
            }
        }

        // ================= RENDER HASIL SCAN =================
        function renderSuccessResult(item, message) {
            const container = document.getElementById('scan-result-container');
            container.innerHTML = `
                <div class="bg-emerald-500 rounded-3xl p-6 text-white shadow-lg shadow-emerald-200/50 transition-all duration-300 transform scale-100 animate-in fade-in">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-xs flex items-center justify-center text-white text-2xl shrink-0">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Verifikasi Berhasil</span>
                                <h3 class="text-xl font-extrabold mt-0.5 leading-tight">Tiket Valid — Siap Naik</h3>
                            </div>
                        </div>
                        <button onclick="dismissResult()" class="text-white/70 hover:text-white p-1 text-lg">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-2xs">
                            <p class="text-emerald-100 text-[10px] uppercase font-medium">Pengunjung</p>
                            <p class="font-bold text-sm text-white truncate">${item.nama_pembeli}</p>
                            <p class="text-emerald-200 text-[11px] font-mono mt-0.5">${item.kode_tiket}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-2xs">
                            <p class="text-emerald-100 text-[10px] uppercase font-medium">Kapal Ditumpangi</p>
                            <p class="font-bold text-sm text-white truncate">${item.kapal.nama_kapal}</p>
                            <p class="text-emerald-200 text-[11px] font-mono mt-0.5">${item.kapal.kode_kapal}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-2xs">
                            <p class="text-emerald-100 text-[10px] uppercase font-medium">Waktu Scan</p>
                            <p class="font-bold text-white">${item.waktu_scan}</p>
                        </div>
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-2xs">
                            <p class="text-emerald-100 text-[10px] uppercase font-medium">Estimasi Trip Selesai</p>
                            <p class="font-bold text-white">${item.estimasi_kembali} WIB (~30 mnt)</p>
                        </div>
                    </div>

                    <div class="mt-3 text-center text-xs text-emerald-100 font-medium">
                        <i class="fa-solid fa-info-circle mr-1"></i> Status kapal otomatis beralih ke <strong>Sedang Berlayar</strong>.
                    </div>
                </div>
            `;

            // Tambahkan entri baru ke live scan log secara visual
            addEntryToLog(item);

            // Reload status kapal berlayar setelah 1 detik
            setTimeout(() => {
                location.reload();
            }, 2500);
        }

        function renderErrorResult(err) {
            const container = document.getElementById('scan-result-container');
            const code = err.code || 'ERROR';
            let title = 'Scan Ditolak!';

            if (code === 'ALREADY_USED') {
                title = '1 Tiket Hanya 1x Perjalanan!';
            } else if (code === 'KAPAL_BUSY') {
                title = 'Kapal Masih Berlayar!';
            } else if (code === 'NOT_FOUND') {
                title = 'Tiket Tidak Ditemukan!';
            }

            container.innerHTML = `
                <div class="bg-rose-600 rounded-3xl p-6 text-white shadow-lg shadow-rose-200/50 transition-all duration-300">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-xs flex items-center justify-center text-white text-2xl shrink-0">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">${code}</span>
                                <h3 class="text-xl font-extrabold mt-0.5 leading-tight">${title}</h3>
                            </div>
                        </div>
                        <button onclick="dismissResult()" class="text-white/70 hover:text-white p-1 text-lg">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/20 text-sm font-medium leading-relaxed bg-white/10 p-4 rounded-2xl backdrop-blur-2xs">
                        ${err.message}
                    </div>

                    <div class="mt-3 text-right">
                        <button onclick="dismissResult()" class="text-xs bg-white text-rose-700 font-bold px-4 py-2 rounded-xl hover:bg-rose-50 transition shadow-xs">
                            Mengerti & Tutup
                        </button>
                    </div>
                </div>
            `;
        }

        function dismissResult() {
            const container = document.getElementById('scan-result-container');
            container.classList.add('hidden');
            container.innerHTML = '';
        }

        function addEntryToLog(item) {
            const logContainer = document.getElementById('live-scan-log');
            const emptyEl = logContainer.querySelector('.border-dashed');
            if (emptyEl) {
                emptyEl.remove();
            }

            const nowTime = new Date().toTimeString().split(' ')[0];
            const newCard = document.createElement('div');
            newCard.className = 'flex items-center justify-between p-3 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-xs animate-in fade-in';
            newCard.innerHTML = `
                <div class="min-w-0 pr-2">
                    <p class="font-bold text-slate-800 truncate">${item.nama_pembeli}</p>
                    <p class="text-[11px] text-slate-400 font-mono">${item.kode_tiket} • ${item.kapal.nama_kapal}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="font-semibold text-slate-600">${nowTime}</span>
                    <p class="text-[10px] text-emerald-600 font-medium">Berhasil</p>
                </div>
            `;
            logContainer.prepend(newCard);
        }

        function updateStats() {
            const statEl = document.getElementById('stat-scan-hari-ini');
            if (statEl) {
                const current = parseInt(statEl.textContent) || 0;
                statEl.textContent = current + 1;
            }
        }

        // ================= SELESAI TRIP MANUAL =================
        async function selesaiTrip(kapalId, namaKapal) {
            if (!confirm(`Selesaikan trip kapal "${namaKapal}" sekarang? Status kapal akan kembali Tersedia.`)) {
                return;
            }

            try {
                const res = await fetch(`${selesaiTripUrlBase}/${kapalId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                }
            } catch (e) {
                alert('Gagal menghubungi server.');
            }
        }

        // Auto Focus pada kolom input manual saat halaman pertama kali dimuat
        window.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('manual-qr-input');
            if (input) {
                input.focus();
            }
        });
    </script>
</x-app-layout>
