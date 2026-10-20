<?php

namespace App\Models;

use Database\Factories\KapalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'kode_kapal',
    'nama_kapal',
    'tarif_biasa',
    'tarif_weekend',
    'diskon',
    'kapasitas',
    'status',
    'waktu_mulai_trip',
    'foto',
    'deskripsi',
])]
class Kapal extends Model
{
    /** @use HasFactory<KapalFactory> */
    use HasFactory;

    public const STATUS_TERSEDIA = 1;

    public const STATUS_SEDANG_BERLAYAR = 2;

    public const STATUS_MAINTENANCE = 3;

    /**
     * Tipe data casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tarif_biasa' => 'decimal:2',
            'tarif_weekend' => 'decimal:2',
            'diskon' => 'decimal:2',
            'kapasitas' => 'integer',
            'status' => 'integer',
            'waktu_mulai_trip' => 'datetime',
        ];
    }

    /**
     * Cek apakah kapal sedang siap / tersedia untuk disewa.
     */
    public function isTersedia(): bool
    {
        return $this->status === self::STATUS_TERSEDIA;
    }

    /**
     * Cek apakah kapal sedang dipakai/berlayar (tidak bisa di-scan).
     */
    public function isSedangBerlayar(): bool
    {
        return $this->status === self::STATUS_SEDANG_BERLAYAR;
    }

    /**
     * Label teks status kapal untuk tampilan UI.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_TERSEDIA => 'Tersedia',
            self::STATUS_SEDANG_BERLAYAR => 'Sedang Berlayar',
            self::STATUS_MAINTENANCE => 'Maintenance',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Class badge warna untuk status kapal (Tailwind).
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_TERSEDIA => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::STATUS_SEDANG_BERLAYAR => 'bg-sky-50 text-sky-700 border-sky-200 animate-pulse',
            self::STATUS_MAINTENANCE => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }

    /**
     * Hitung tarif aktif hari ini (otomatis cek weekend vs hari biasa).
     */
    public function getTarifHariIniAttribute(): float
    {
        $isWeekend = now()->isWeekend();
        $tarifDasar = $isWeekend ? (float) $this->tarif_weekend : (float) $this->tarif_biasa;

        $diskon = (float) $this->diskon;

        return max(0, $tarifDasar - $diskon);
    }
}
