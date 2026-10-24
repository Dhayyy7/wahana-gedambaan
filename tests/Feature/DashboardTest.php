<?php

namespace Tests\Feature;

use App\Models\Kapal;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_dapat_diakses_oleh_user_terautentikasi_dan_menampilkan_data_dinamis(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $kapal = Kapal::factory()->create(['nama_kapal' => 'Speedboat Pelangi']);

        Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'nama_pembeli' => 'Bambang Sudirman',
            'status' => Tiket::STATUS_SELESAI,
            'tanggal_pemesanan' => now(),
            'total_bayar' => 125000,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Selamat datang kembali');
        $response->assertSee('Bambang Sudirman');
        $response->assertSee('Speedboat Pelangi');
        $response->assertSee('Total Pemesanan');
        $response->assertSee('Status Armada Speedboat');
    }
}
