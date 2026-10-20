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
        Schema::create('kapals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kapal')->unique()->comment('Kode unik kapal, misal: SB-01');
            $table->string('nama_kapal')->comment('Nama kapal, misal: Speed Boat 01');
            $table->decimal('tarif_biasa', 12, 2)->comment('Tarif hari biasa (Senin - Jumat)');
            $table->decimal('tarif_weekend', 12, 2)->comment('Tarif akhir pekan (Sabtu - Minggu)');
            $table->decimal('diskon', 10, 2)->default(0)->comment('Nilai diskon (default 0)');
            $table->unsignedInteger('kapasitas')->default(6)->comment('Kapasitas maksimal penumpang');
            $table->tinyInteger('status')->default(1)->comment('1: Tersedia/Ready, 2: Sedang Berlayar/In Process, 3: Maintenance');
            $table->timestamp('waktu_mulai_trip')->nullable()->comment('Waktu kapal mulai berlayar/di-scan operator untuk acuan cron job 30 menit');
            $table->string('foto')->nullable()->comment('Path gambar/foto kapal');
            $table->text('deskripsi')->nullable()->comment('Keterangan atau spesifikasi kapal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapals');
    }
};
