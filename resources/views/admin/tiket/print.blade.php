<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Tiket - {{ $tiket->kode_tiket }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            size: 58mm 155mm;
            margin: 0;
        }

        html, body {
            width: 58mm;
            margin: 0 auto;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', Courier, monospace, 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .receipt-container {
            width: 58mm;
            max-width: 58mm;
            padding: 2.5mm 3.5mm;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .logo {
            max-height: 34px;
            width: auto;
            margin: 0 auto 3px;
            display: block;
            filter: grayscale(100%) contrast(150%);
        }

        .title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 9.5px;
            margin-top: 1px;
        }

        .divider-solid {
            border-top: 1px solid #000;
            margin: 4px 0;
        }

        .divider-dashed {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        .divider-double {
            border-top: 2px solid #000;
            margin: 4px 0;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.info-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        table.info-table td.col-label {
            width: 32%;
            white-space: nowrap;
        }

        table.info-table td.col-sep {
            width: 4%;
            text-align: center;
        }

        table.info-table td.col-val {
            width: 64%;
            word-break: break-word;
        }

        .qr-section {
            text-align: center;
            margin: 6px 0 4px;
        }

        .qr-box {
            display: inline-block;
            padding: 2px;
            background: #fff;
        }

        .qr-box svg {
            width: 110px !important;
            height: 110px !important;
            display: block;
            margin: 0 auto;
        }

        .qr-text {
            font-size: 7.5px;
            letter-spacing: -0.2px;
            margin-top: 2px;
            word-break: break-all;
            font-family: monospace;
        }

        table.calc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        table.calc-table td {
            padding: 1.5px 0;
        }

        .total-row td {
            font-size: 12px;
            font-weight: bold;
            padding-top: 3px;
        }

        .footer-note {
            text-align: center;
            font-size: 8.5px;
            line-height: 1.25;
            margin-top: 4px;
            padding-top: 2px;
        }

        .footer-thanks {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        /* Action bar for screen view (hidden on print) */
        .no-print-bar {
            background: #f1f5f9;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 10px;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 12px;
        }

        .btn-print {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-close {
            background: #64748b;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            margin-left: 6px;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }
            body, html {
                margin: 0 !important;
                padding: 0 !important;
                width: 58mm !important;
            }
            .receipt-container {
                width: 58mm !important;
                max-width: 58mm !important;
                padding: 2mm 3mm !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button type="button" class="btn-print" onclick="window.print()">🖨️ Cetak Ulang</button>
        <button type="button" class="btn-close" onclick="window.close()">Tutup</button>
    </div>

    <div class="receipt-container">
        <!-- Header -->
        <div class="text-center">
            <img src="{{ asset('images/asset_login/logo.png') }}" alt="Logo" class="logo">
            <div class="title">Wahana Gedambaan</div>
            <div class="subtitle">Speed Boat Adventure</div>
        </div>

        <div class="divider-double"></div>

        @if ($tiket->status === -1)
            <div style="border: 2px dashed #000; padding: 4px; text-align: center; margin: 4px 0; font-weight: bold; font-size: 11px; letter-spacing: 0.5px;">
                *** TIKET DIBATALKAN / VOID ***
            </div>
            <div class="divider-dashed"></div>
        @endif

        <!-- Info Tiket -->
        <table class="info-table">
            <tr>
                <td class="col-label">No. Tiket</td>
                <td class="col-sep">:</td>
                <td class="col-val font-bold">{{ $tiket->kode_tiket }}</td>
            </tr>
            <tr>
                <td class="col-label">Waktu</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $tiket->tanggal_pemesanan->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="col-label">Kasir</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ Auth::user()->name }}</td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        <!-- Info Pengunjung & Kapal -->
        <table class="info-table">
            <tr>
                <td class="col-label">Pengunjung</td>
                <td class="col-sep">:</td>
                <td class="col-val font-bold">{{ $tiket->nama_pembeli }}</td>
            </tr>
            <tr>
                <td class="col-label">No. HP</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $tiket->no_hp }}</td>
            </tr>
            @if ($tiket->alamat)
            <tr>
                <td class="col-label">Alamat</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $tiket->alamat }}</td>
            </tr>
            @endif
            <tr>
                <td class="col-label">Kapal</td>
                <td class="col-sep">:</td>
                <td class="col-val font-bold">{{ $tiket->kapal->nama_kapal }}</td>
            </tr>
            <tr>
                <td class="col-label">Hari</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $tiket->is_weekend ? 'Weekend' : 'Hari Biasa' }}</td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        <!-- QR Code -->
        <div class="qr-section">
            <div class="qr-box">
                {!! QrCode::size(110)->margin(0)->generate($tiket->qr_code) !!}
            </div>
            <div class="qr-text">{{ $tiket->qr_code }}</div>
        </div>

        <div class="divider-dashed"></div>

        <!-- Rincian Biaya -->
        <table class="calc-table">
            <tr>
                <td>Tarif ({{ $tiket->is_weekend ? 'Weekend' : 'Biasa' }})</td>
                <td class="text-right">Rp {{ number_format($tiket->tarif, 0, ',', '.') }}</td>
            </tr>
            @if ($tiket->diskon > 0)
            <tr>
                <td>Diskon</td>
                <td class="text-right">- Rp {{ number_format($tiket->diskon, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="font-bold">TOTAL BAYAR</td>
                <td class="text-right font-bold">Rp {{ number_format($tiket->total_bayar, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider-double"></div>

        <!-- Footer -->
        <div class="footer-note">
            Tiket hanya berlaku 1x perjalanan.<br>
            Tunjukkan QR Code ke petugas kapal.
        </div>
        <div class="footer-thanks">
            *** TERIMA KASIH ***
        </div>
    </div>

    <script>
        // Otomatis buka dialog cetak saat halaman dibuka langsung di tab baru (bukan di dalam iframe)
        if (window.self === window.top) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 300);
            });
        }
    </script>
</body>
</html>
