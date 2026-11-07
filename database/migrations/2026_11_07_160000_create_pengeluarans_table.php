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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengeluaran', 30)->unique()->comment('Kode unik pengeluaran, contoh: OUT-20261107-0001');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Petugas/Kasir yang mencatat pengeluaran');
            $table->date('tanggal_pengeluaran')->comment('Tanggal terjadinya pengeluaran');
            $table->string('nama_pengeluaran', 150)->comment('Nama atau keperluan pengeluaran');
            $table->decimal('nominal', 12, 2)->comment('Nominal pengeluaran dalam Rupiah');
            $table->text('keterangan')->nullable()->comment('Catatan atau keterangan tambahan pengeluaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};

