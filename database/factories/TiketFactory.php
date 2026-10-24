<?php

namespace Database\Factories;

use App\Models\Kapal;
use App\Models\Tiket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tiket>
 */
class TiketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_tiket' => Tiket::generateKodeTiket(),
            'kapal_id' => Kapal::factory(),
            'nama_pembeli' => fake()->name(),
            'no_hp' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'tarif' => 125000,
            'diskon' => 0,
            'total_bayar' => 125000,
            'is_weekend' => false,
            'qr_code' => Str::uuid()->toString(),
            'status' => Tiket::STATUS_PENDING,
            'tanggal_pemesanan' => now(),
        ];
    }
}
