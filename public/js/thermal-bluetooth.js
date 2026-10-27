/**
 * Wahana Gedambaan - Web Bluetooth Thermal Printer Helper
 * Mendukung pencetakan langsung ke printer termal 58mm/80mm via Web Bluetooth API (BLE / ESC-POS).
 */

(function (window) {
    'use strict';

    // Daftar UUID Primary Service yang umum digunakan oleh printer termal Bluetooth BLE
    const PRINTER_SERVICES = [
        '000018f0-0000-1000-8000-00805f9b34fb', // Standard POS BLE Service
        'e7810a71-73ae-499d-8c15-faa9aef0c3f2', // Posiflex / Common Mini POS
        '49535343-fe7d-4ae5-8fa9-9fafd205e455', // ISSC BLE UART
        '0000ffe0-0000-1000-8000-00805f9b34fb', // Common BLE Serial UART (Goojprt, Panda, Xprinter)
        '6e400001-b5a3-f393-e0a9-e50e24dcca9e', // Nordic Semiconductor UART Service
        '0000ff00-0000-1000-8000-00805f9b34fb'  // Alternative BLE Serial
    ];

    class ThermalBluetoothManager {
        constructor() {
            this.device = null;
            this.characteristic = null;
            this.isConnecting = false;
            this.lastDeviceName = localStorage.getItem('last_bt_printer_name') || null;

            // Inisialisasi status setelah DOM siap
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.initUI());
            } else {
                this.initUI();
            }
        }

        isSupported() {
            return typeof navigator !== 'undefined' && 'bluetooth' in navigator;
        }

        isSecure() {
            return typeof window !== 'undefined' && Boolean(window.isSecureContext);
        }

        isConnected() {
            return Boolean(this.device && this.device.gatt && this.device.gatt.connected && this.characteristic);
        }

        escapeHtml(str) {
            return String(str || '').replace(/[&<>"']/g, function(s) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[s];
            });
        }

        initUI() {
            this.updateUI();
        }

        /**
         * Scan dan hubungkan ke printer Bluetooth
         */
        async connect() {
            if (!this.isSupported()) {
                if (!this.isSecure()) {
                    throw new Error("Fitur Web Bluetooth memerlukan koneksi aman (HTTPS) atau diakses via 'http://localhost'.");
                }
                throw new Error("Browser Anda belum mendukung Web Bluetooth API. Harap gunakan Google Chrome atau Microsoft Edge.");
            }

            try {
                this.isConnecting = true;
                this.updateUI();

                this.showToast('Membuka pemindai Bluetooth... Pilih printer termal Anda.', 'info');

                // Minta izin ke browser untuk memilih perangkat Bluetooth
                this.device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: PRINTER_SERVICES
                });

                this.showToast(`Menghubungkan ke ${this.device.name || 'Printer'}...`, 'info');

                // Hubungkan ke GATT Server
                const server = await this.device.gatt.connect();

                // Cari service yang didukung oleh printer
                this.characteristic = null;
                for (const serviceUuid of PRINTER_SERVICES) {
                    try {
                        const service = await server.getPrimaryService(serviceUuid);
                        const characteristics = await service.getCharacteristics();

                        for (const char of characteristics) {
                            if (char.properties.write || char.properties.writeWithoutResponse) {
                                this.characteristic = char;
                                break;
                            }
                        }
                        if (this.characteristic) break;
                    } catch (err) {
                        // Coba service selanjutnya
                    }
                }

                if (!this.characteristic) {
                    throw new Error("Terhubung ke perangkat, namun tidak menemukan jalur kirim data (Write Characteristic). Pastikan ini adalah Printer Termal BLE.");
                }

                // Simpan nama printer ke localStorage
                const devName = this.device.name || 'Printer Termal BT';
                this.lastDeviceName = devName;
                localStorage.setItem('last_bt_printer_name', devName);

                // Pasang listener jika koneksi terputus
                this.device.addEventListener('gattserverdisconnected', () => {
                    this.onDisconnected();
                });

                this.isConnecting = false;
                this.updateUI();
                this.showToast(`✓ Berhasil terhubung ke ${devName}!`, 'success');

                return true;
            } catch (error) {
                this.isConnecting = false;
                this.characteristic = null;
                this.updateUI();

                if (error.name === 'NotFoundError') {
                    // Pengguna membatalkan dialog pemilihan printer
                    this.showToast('Pencarian printer dibatalkan.', 'info');
                    return false;
                }

                console.error('Bluetooth Connection Error:', error);
                this.showToast(`Gagal: ${error.message}`, 'error');
                throw error;
            }
        }

        /**
         * Putuskan koneksi printer
         */
        disconnect() {
            if (this.device && this.device.gatt && this.device.gatt.connected) {
                this.device.gatt.disconnect();
            }
            this.onDisconnected();
            this.showToast('Koneksi printer Bluetooth diputuskan.', 'info');
        }

        onDisconnected() {
            this.characteristic = null;
            this.updateUI();
        }

        /**
         * Mengirim raw bytes ke printer Bluetooth dengan throttling (chunking)
         */
        async sendBytes(byteArray) {
            if (!this.isConnected()) {
                throw new Error("Printer Bluetooth belum terhubung!");
            }

            const data = byteArray instanceof Uint8Array ? byteArray : new Uint8Array(byteArray);
            const chunkSize = 40; // Potongan aman untuk BLE buffer

            for (let i = 0; i < data.length; i += chunkSize) {
                const chunk = data.slice(i, i + chunkSize);
                if (this.characteristic.properties.writeWithoutResponse) {
                    await this.characteristic.writeValueWithoutResponse(chunk);
                } else {
                    await this.characteristic.writeValue(chunk);
                }
                // Beri jeda 20ms agar printer sempat memproses
                await new Promise(r => setTimeout(r, 20));
            }
        }

        /**
         * Cetak tiket transaksi
         */
        async printTicket(tiket) {
            if (!this.isConnected()) {
                const shouldConnect = confirm("Printer Bluetooth belum terhubung. Hubungkan printer sekarang?");
                if (shouldConnect) {
                    const connected = await this.connect();
                    if (!connected) return false;
                } else {
                    return false;
                }
            }

            try {
                this.showToast(`Mengirim tiket ${tiket.kode_tiket} ke printer...`, 'info');
                const bytes = this.buildTicketEscPos(tiket);
                await this.sendBytes(bytes);
                this.showToast(`✓ Tiket ${tiket.kode_tiket} berhasil dicetak!`, 'success');
                return true;
            } catch (err) {
                console.error('Print ticket error:', err);
                this.showToast(`Gagal mencetak tiket: ${err.message}`, 'error');
                return false;
            }
        }

        /**
         * Cetak halaman uji coba (Test Print)
         */
        async printTest() {
            if (!this.isConnected()) {
                alert("Hubungkan printer Bluetooth terlebih dahulu!");
                return;
            }

            try {
                this.showToast('Mencetak struk tes...', 'info');
                const bytes = this.buildTestEscPos();
                await this.sendBytes(bytes);
                this.showToast('✓ Struk tes berhasil dicetak!', 'success');
            } catch (err) {
                console.error('Test print error:', err);
                this.showToast(`Gagal: ${err.message}`, 'error');
            }
        }

        /**
         * Format baris 2 kolom rata kiri & kanan (Lebar 32 karakter untuk 58mm)
         */
        formatTwoCols(left, right, width = 32) {
            left = String(left || '');
            right = String(right || '');
            const spaces = width - left.length - right.length;
            if (spaces <= 0) {
                return left + ' ' + right + '\n';
            }
            return left + ' '.repeat(spaces) + right + '\n';
        }

        /**
         * Format baris label dan nilai
         */
        formatRow(label, val, labelWidth = 11, width = 32) {
            label = String(label || '').padEnd(labelWidth, ' ');
            const prefix = label + ': ';
            const valStr = String(val || '');
            return prefix + valStr + '\n';
        }

        /**
         * Buat byte array ESC/POS untuk tiket
         */
        buildTicketEscPos(t) {
            const bytes = [];
            const encoder = new TextEncoder();

            const appendStr = (str) => {
                const encoded = encoder.encode(str);
                for (let i = 0; i < encoded.length; i++) {
                    bytes.push(encoded[i]);
                }
            };

            const appendBytes = (arr) => {
                for (let i = 0; i < arr.length; i++) {
                    bytes.push(arr[i]);
                }
            };

            // 1. Inisialisasi printer
            appendBytes([0x1B, 0x40]); // ESC @ (Reset)

            // 2. Header
            appendBytes([0x1B, 0x61, 0x01]); // Align Center
            appendBytes([0x1B, 0x45, 0x01]); // Bold ON
            appendBytes([0x1D, 0x21, 0x01]); // Double height
            appendStr("WAHANA GEDAMBAAN\n");
            appendBytes([0x1D, 0x21, 0x00]); // Normal size
            appendBytes([0x1B, 0x45, 0x00]); // Bold OFF
            appendStr("Speed Boat Adventure\n");
            appendStr("================================\n");

            // Status Batal / Void
            if (t.status === -1 || t.status === '-1') {
                appendBytes([0x1B, 0x45, 0x01]);
                appendStr("*** TIKET DIBATALKAN / VOID ***\n");
                appendBytes([0x1B, 0x45, 0x00]);
                appendStr("--------------------------------\n");
            }

            // 3. Info Tiket
            appendBytes([0x1B, 0x61, 0x00]); // Align Left
            appendStr(this.formatRow("No. Tiket", t.kode_tiket));
            appendStr(this.formatRow("Waktu", t.waktu));
            appendStr(this.formatRow("Kasir", t.kasir));
            appendStr("--------------------------------\n");

            // 4. Data Pengunjung & Kapal
            appendStr(this.formatRow("Pengunjung", t.nama_pembeli));
            appendStr(this.formatRow("No. HP", t.no_hp));
            if (t.alamat) {
                appendStr(this.formatRow("Alamat", t.alamat));
            }
            appendStr(this.formatRow("Kapal", t.kapal));
            appendStr(this.formatRow("Hari", t.hari));
            appendStr("--------------------------------\n");

            // 5. QR Code (ESC/POS Native Command)
            if (t.qr_code) {
                appendBytes([0x1B, 0x61, 0x01]); // Align Center
                const qrTextBytes = encoder.encode(t.qr_code);
                const qrLen = qrTextBytes.length + 3;
                const pL = qrLen % 256;
                const pH = Math.floor(qrLen / 256);

                // Setup & Print QR ESC/POS
                appendBytes([
                    0x1D, 0x28, 0x6B, 0x04, 0x00, 0x31, 0x41, 0x32, 0x00, // Model 2
                    0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x43, 0x06,       // Module Size 6
                    0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x45, 0x31,       // Error Level M
                    0x1D, 0x28, 0x6B, pL, pH, 0x31, 0x50, 0x30            // Store Data
                ]);
                appendBytes(Array.from(qrTextBytes));
                appendBytes([0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x51, 0x30]); // Print QR

                // Teks UUID / Kode di bawah QR
                appendStr("\n" + t.qr_code + "\n");
                appendStr("--------------------------------\n");
            }

            // 6. Rincian Biaya
            appendBytes([0x1B, 0x61, 0x00]); // Align Left
            appendStr(this.formatTwoCols("Tarif (" + t.hari + ")", "Rp " + t.tarif));
            if (t.diskon) {
                appendStr(this.formatTwoCols("Diskon", "-Rp " + t.diskon));
            }
            appendBytes([0x1B, 0x45, 0x01]); // Bold ON
            appendStr(this.formatTwoCols("TOTAL BAYAR", "Rp " + t.total));
            appendBytes([0x1B, 0x45, 0x00]); // Bold OFF
            appendStr("================================\n");

            // 7. Footer
            appendBytes([0x1B, 0x61, 0x01]); // Align Center
            appendStr("Tiket hanya berlaku 1x perjalanan.\n");
            appendStr("Tunjukkan QR ke petugas kapal.\n");
            appendBytes([0x1B, 0x45, 0x01]);
            appendStr("*** TERIMA KASIH ***\n");
            appendBytes([0x1B, 0x45, 0x00]);

            // Feed & Cut
            appendStr("\n\n\n\n");
            appendBytes([0x1D, 0x56, 0x42, 0x00]); // GS V B 0 (Cut Paper)

            return new Uint8Array(bytes);
        }

        /**
         * Buat byte array ESC/POS untuk tes cetak
         */
        buildTestEscPos() {
            const bytes = [];
            const encoder = new TextEncoder();

            const appendStr = (str) => {
                const encoded = encoder.encode(str);
                for (let i = 0; i < encoded.length; i++) {
                    bytes.push(encoded[i]);
                }
            };
            const appendBytes = (arr) => {
                for (let i = 0; i < arr.length; i++) {
                    bytes.push(arr[i]);
                }
            };

            appendBytes([0x1B, 0x40]); // Reset
            appendBytes([0x1B, 0x61, 0x01]); // Center
            appendBytes([0x1B, 0x45, 0x01]); // Bold
            appendStr("WAHANA GEDAMBAAN\n");
            appendBytes([0x1B, 0x45, 0x00]);
            appendStr("Tes Koneksi Bluetooth Sukses!\n");
            appendStr("================================\n");
            appendBytes([0x1B, 0x61, 0x00]);
            appendStr("Device : " + (this.device ? this.device.name : 'Printer') + "\n");
            appendStr("Waktu  : " + new Date().toLocaleString('id-ID') + "\n");
            appendStr("Lebar  : 58mm (32 Karakter)\n");
            appendStr("Status : Siap Cetak Tiket\n");
            appendStr("--------------------------------\n");
            appendBytes([0x1B, 0x61, 0x01]);
            appendStr("*** SIAP DIGUNAKAN ***\n\n\n\n");
            appendBytes([0x1D, 0x56, 0x42, 0x00]);

            return new Uint8Array(bytes);
        }

        /**
         * Perbarui status UI pada tombol dan modal
         */
        updateUI() {
            const isConn = this.isConnected();
            const devName = this.device ? (this.device.name || 'Printer BT') : this.lastDeviceName;

            // Header status
            const dot = document.getElementById('bt-status-dot');
            const text = document.getElementById('bt-header-text');
            const modalStatus = document.getElementById('bt-modal-status');
            const btnScan = document.getElementById('bt-btn-scan');
            const btnTest = document.getElementById('bt-btn-test');
            const btnDisconnect = document.getElementById('bt-btn-disconnect');

            if (dot) {
                if (this.isConnecting) {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping';
                } else if (isConn) {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-300';
                } else {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-slate-300';
                }
            }

            if (text) {
                if (this.isConnecting) {
                    text.innerText = 'Menghubungkan...';
                } else if (isConn) {
                    text.innerText = `${devName} (Aktif)`;
                } else {
                    text.innerText = 'Printer Bluetooth';
                }
            }

            if (modalStatus) {
                if (this.isConnecting) {
                    modalStatus.innerHTML = `
                        <div class="flex items-center gap-2.5 text-amber-700 bg-amber-50 border border-amber-200 px-4 py-3 rounded-xl text-xs font-medium">
                            <i class="fa-solid fa-spinner fa-spin text-sm"></i>
                            <span>Sedang menghubungkan ke printer Bluetooth...</span>
                        </div>
                    `;
                } else if (isConn) {
                    modalStatus.innerHTML = `
                        <div class="flex items-center justify-between text-emerald-800 bg-emerald-50 border border-emerald-200 px-4 py-3 rounded-xl text-xs font-medium">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span>Terhubung: <strong>${this.escapeHtml(devName)}</strong></span>
                            </div>
                            <span class="text-[11px] bg-emerald-200/60 text-emerald-800 px-2 py-0.5 rounded-md font-semibold">Siap Cetak</span>
                        </div>
                    `;
                } else {
                    modalStatus.innerHTML = `
                        <div class="flex items-center gap-2.5 text-slate-600 bg-slate-50 border border-slate-200 px-4 py-3 rounded-xl text-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                            <span>Belum terhubung ke printer Bluetooth.</span>
                        </div>
                    `;
                }
            }

            if (btnTest) {
                btnTest.disabled = !isConn;
                btnTest.classList.toggle('opacity-50', !isConn);
                btnTest.classList.toggle('cursor-not-allowed', !isConn);
            }

            if (btnDisconnect) {
                btnDisconnect.style.display = isConn ? 'inline-flex' : 'none';
            }

            if (btnScan) {
                btnScan.disabled = this.isConnecting;
                btnScan.innerText = this.isConnecting ? 'Mencari...' : (isConn ? 'Ganti Printer' : '🔍 Scan & Hubungkan');
            }
        }

        showToast(message, type = 'info') {
            let container = document.getElementById('bt-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'bt-toast-container';
                container.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm pointer-events-none';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            const bgClass = type === 'success' ? 'bg-emerald-600 text-white' : (type === 'error' ? 'bg-rose-600 text-white' : 'bg-slate-900 text-white');
            toast.className = `${bgClass} px-4 py-3 rounded-2xl shadow-xl text-xs font-medium flex items-center gap-2.5 transition-all duration-300 transform translate-y-3 opacity-0 pointer-events-auto`;

            let icon = '<i class="fa-solid fa-circle-info"></i>';
            if (type === 'success') icon = '<i class="fa-solid fa-circle-check"></i>';
            if (type === 'error') icon = '<i class="fa-solid fa-triangle-exclamation"></i>';

            toast.innerHTML = `${icon}<span>${message}</span>`;
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-3', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }
    }

    // Instansiasi Global
    window.ThermalBluetooth = new ThermalBluetoothManager();

})(window);
