<?php

namespace Database\Factories;

use App\Models\Kapal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kapal>
 */
class KapalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_kapal' => 'SB-'.fake()->unique()->numerify('###'),
            'nama_kapal' => 'Speed Boat '.fake()->firstName(),
            'tarif_biasa' => fake()->randomElement([150000, 200000, 250000, 300000]),
            'tarif_weekend' => fake()->randomElement([200000, 250000, 300000, 350000]),
            'diskon' => fake()->randomElement([0, 10000, 25000]),
            'kapasitas' => fake()->randomElement([4, 6, 8, 10]),
            'status' => Kapal::STATUS_TERSEDIA,
            'waktu_mulai_trip' => null,
            'foto' => null,
            'deskripsi' => fake()->sentence(),
        ];
    }
}
