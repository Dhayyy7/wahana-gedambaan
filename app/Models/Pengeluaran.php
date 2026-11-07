<?php

namespace App\Models;

use Database\Factories\PengeluaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Pengeluaran extends Model
{
    /** @use HasFactory<PengeluaranFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'kode_pengeluaran',
        'user_id',
        'tanggal_pengeluaran',
        'nama_pengeluaran',
        'nominal',
        'keterangan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengeluaran' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke petugas/kasir yang menginput pengeluaran.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate kode pengeluaran unik berformat OUT-YYYYMMDD-XXXX.
     */
    public static function generateKodePengeluaran(?string $tanggalInput = null): string
    {
        $tanggal = $tanggalInput
            ? Carbon::parse($tanggalInput)->format('Ymd')
            : now()->format('Ymd');

        $prefix = "OUT-{$tanggal}-";

        $last = static::where('kode_pengeluaran', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $urutan = $last
            ? (int) substr($last->kode_pengeluaran, strlen($prefix)) + 1
            : 1;

        return $prefix.str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
    }
}

