<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tiket extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'kode_tiket',
        'kapal_id',
        'nama_pembeli',
        'no_hp',
        'alamat',
        'tarif',
        'diskon',
        'total_bayar',
        'is_weekend',
        'qr_code',
        'status',
        'tanggal_pemesanan',
        'tanggal_scan',
    ];

    public const STATUS_PENDING = 0;

    public const STATUS_SELESAI = 1;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tarif' => 'decimal:2',
            'diskon' => 'decimal:2',
            'total_bayar' => 'decimal:2',
            'is_weekend' => 'boolean',
            'status' => 'integer',
            'tanggal_pemesanan' => 'datetime',
            'tanggal_scan' => 'datetime',
        ];
    }

    /**
     * Relasi ke kapal yang dipesan.
     */
    public function kapal(): BelongsTo
    {
        return $this->belongsTo(Kapal::class);
    }

    /**
     * Apakah tiket sudah digunakan/di-scan operator?
     */
    public function isSudahDigunakan(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    /**
     * Label status tiket untuk tampilan UI.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Belum Digunakan',
            self::STATUS_SELESAI => 'Sudah Digunakan',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Class badge warna untuk status tiket.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-amber-50 text-amber-700 border-amber-200',
            self::STATUS_SELESAI => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-slate-50 text-slate-600 border-slate-200',
        };
    }

    /**
     * Generate kode tiket unik berformat TKT-YYYYMMDD-XXXX.
     */
    public static function generateKodeTiket(): string
    {
        $tanggal = now()->format('Ymd');
        $prefix = "TKT-{$tanggal}-";

        // Cari nomor urut terakhir hari ini
        $last = static::where('kode_tiket', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $urutan = $last
            ? (int) substr($last->kode_tiket, strlen($prefix)) + 1
            : 1;

        return $prefix.str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}
