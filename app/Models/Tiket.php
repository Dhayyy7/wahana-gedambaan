<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

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
        'metode_pembayaran',
        'is_weekend',
        'qr_code',
        'status',
        'tanggal_pemesanan',
        'tanggal_scan',
    ];

    public const STATUS_BATAL = -1;

    public const STATUS_PENDING = 0;

    public const STATUS_SELESAI = 1;

    public const EXPIRE_HOURS = 12;

    public const METODE_TUNAI = 'tunai';

    public const METODE_QRIS = 'qris';

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
     * Apakah tiket sudah dibatalkan?
     */
    public function isDibatalkan(): bool
    {
        return $this->status === self::STATUS_BATAL;
    }

    /**
     * Apakah tiket masih pending (belum digunakan & belum dibatalkan)?
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Apakah tiket sudah digunakan/di-scan operator?
     */
    public function isSudahDigunakan(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    /**
     * Apakah tiket sudah melewati batas masa berlaku 12 jam sejak waktu pemesanan?
     */
    public function isExpired(): bool
    {
        return $this->tanggal_pemesanan !== null
            && $this->tanggal_pemesanan->lte(now()->subHours(self::EXPIRE_HOURS));
    }

    /**
     * Apakah tiket diselesaikan secara otomatis setelah 12 jam (disobek manual tanpa scan QR)?
     */
    public function isAutoSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI && $this->tanggal_scan === null;
    }

    /**
     * Batas waktu berlaku tiket (12 jam sejak tanggal_pemesanan).
     */
    public function getWaktuKadaluarsaAttribute(): ?Carbon
    {
        return $this->tanggal_pemesanan?->copy()->addHours(self::EXPIRE_HOURS);
    }

    /**
     * Apakah tiket masih bisa dibatalkan oleh kasir/admin?
     */
    public function canBeCancelled(): bool
    {
        return $this->status === self::STATUS_PENDING && ! $this->isExpired();
    }

    /**
     * Pastikan kolom metode_pembayaran tersedia di tabel tikets
     * (berguna jika di-deploy ke shared hosting tanpa akses terminal/SSH untuk php artisan migrate).
     */
    public static function ensureSchemaUpdated(): void
    {
        try {
            if (Cache::get('schema_tikets_metode_pembayaran_ready')) {
                return;
            }

            if (Schema::hasTable('tikets')) {
                if (! Schema::hasColumn('tikets', 'metode_pembayaran')) {
                    Schema::table('tikets', function (Blueprint $table) {
                        $table->string('metode_pembayaran', 20)
                            ->nullable()
                            ->after('total_bayar')
                            ->comment('Metode pembayaran: tunai, qris, atau null untuk data lama');
                    });
                }

                Cache::forever('schema_tikets_metode_pembayaran_ready', true);
            }
        } catch (\Throwable) {
            // Abaikan jika koneksi DB belum siap
        }
    }

    /**
     * Otomatis ubah tiket berstatus Pending yang sudah melewati 12 jam
     * sejak tanggal_pemesanan menjadi Selesai (Sudah Digunakan) tanpa mengisi tanggal_scan,
     * untuk mengakomodasi tiket yang disobek manual oleh operator tanpa scan QR.
     */
    public static function syncExpiredTickets(): int
    {
        static::ensureSchemaUpdated();

        return static::where('status', self::STATUS_PENDING)
            ->where('tanggal_pemesanan', '<=', now()->subHours(self::EXPIRE_HOURS))
            ->update([
                'status' => self::STATUS_SELESAI,
            ]);
    }

    /**
     * Label status tiket untuk tampilan UI.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_BATAL => 'Dibatalkan',
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
            self::STATUS_BATAL => 'bg-rose-50 text-rose-700 border-rose-200',
            self::STATUS_PENDING => 'bg-amber-50 text-amber-700 border-amber-200',
            self::STATUS_SELESAI => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-slate-50 text-slate-600 border-slate-200',
        };
    }

    /**
     * Label metode pembayaran untuk tampilan UI & laporan.
     */
    public function getMetodePembayaranLabelAttribute(): string
    {
        return match ($this->metode_pembayaran) {
            self::METODE_QRIS => 'QRIS',
            self::METODE_TUNAI => 'Tunai',
            default => '-',
        };
    }

    /**
     * Class badge warna untuk metode pembayaran.
     */
    public function getMetodePembayaranBadgeClassAttribute(): string
    {
        return match ($this->metode_pembayaran) {
            self::METODE_QRIS => 'bg-sky-50 text-sky-700 border-sky-200',
            self::METODE_TUNAI => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-slate-50 text-slate-400 border-slate-200',
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
