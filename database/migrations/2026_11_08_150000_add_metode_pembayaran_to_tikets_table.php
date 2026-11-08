<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tikets', function (Blueprint $table) {
            $table->string('metode_pembayaran', 20)
                ->nullable()
                ->after('total_bayar')
                ->comment('Metode pembayaran: tunai, qris, atau null untuk data lama');
        });
    }

    public function down(): void
    {
        Schema::table('tikets', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};

