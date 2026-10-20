<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();

            // Kode tiket unik yang akan dicetak & dijadikan QR
            $table->string('kode_tiket', 30)->unique()->comment('Kode unik tiket, contoh: TKT-20261018-0001');

            // Relasi ke kapal
            $table->foreignId('kapal_id')->constrained('kapals')->restrictOnDelete();

            // Data Pelanggan (walk-in, langsung di tabel)
            $table->string('nama_pembeli', 100)->comment('Nama lengkap pengunjung');
            $table->string('no_hp', 20)->comment('Nomor HP pengunjung');
            $table->text('alamat')->nullable()->comment('Alamat pengunjung');

            // Snapshot harga saat tiket dibuat (agar laporan tetap akurat walau tarif berubah)
            $table->decimal('tarif', 12, 2)->comment('Tarif kapal saat tiket dibuat (snapshot)');
            $table->decimal('diskon', 12, 2)->default(0)->comment('Diskon yang berlaku saat tiket dibuat');
            $table->decimal('total_bayar', 12, 2)->comment('Total yang dibayar (tarif - diskon)');
            $table->boolean('is_weekend')->default(false)->comment('True jika transaksi di hari weekend');

            // QR Code
            $table->string('qr_code', 100)->unique()->comment('String hash unik sebagai payload QR Code');

            // Status & Timestamp Alur
            $table->tinyInteger('status')->default(0)->comment('0: Pending/Belum Digunakan, 1: Sudah Digunakan/Selesai');
            $table->timestamp('tanggal_pemesanan')->useCurrent()->comment('Waktu kasir membuat tiket');
            $table->timestamp('tanggal_scan')->nullable()->comment('Waktu operator menscan tiket, null jika belum digunakan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
