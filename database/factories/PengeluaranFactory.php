<?php

namespace Database\Factories;

use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengeluaran>
 */
class PengeluaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $sequence = 1;

        return [
            'kode_pengeluaran' => sprintf('OUT-%s-%04d', now()->format('Ymd'), $sequence++),
            'user_id' => User::factory(),
            'tanggal_pengeluaran' => now()->toDateString(),
            'nama_pengeluaran' => fake()->sentence(3),
            'nominal' => 50000,
            'keterangan' => fake()->sentence(),
        ];
    }
}

