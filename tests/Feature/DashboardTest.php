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

    public function test_dashboard_superadmin_menampilkan_data_lengkap_dan_dinamis(): void
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
        $response->assertSee('Panel Superadmin');
        $response->assertSee('Bambang Sudirman');
        $response->assertSee('Speedboat Pelangi');
        $response->assertSee('Total Pemesanan');
        $response->assertSee('Status Armada Speedboat');
    }

    public function test_dashboard_kasir_menyesuaikan_konten_ke_penjualan_dan_loket(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $kapal = Kapal::factory()->create(['nama_kapal' => 'Speedboat Bintang']);

        Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'nama_pembeli' => 'Siti Aminah',
            'status' => Tiket::STATUS_PENDING,
            'tanggal_pemesanan' => now(),
            'total_bayar' => 80000,
        ]);

        $response = $this->actingAs($kasir)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Loket Kasir Tiket');
        $response->assertSee('Tiket Terjual Hari Ini');
        $response->assertSee('Pendapatan Hari Ini');
        $response->assertSee('Omset Bulan Ini');
        $response->assertSee('Siti Aminah');
    }

    public function test_dashboard_operator_menyesuaikan_konten_ke_scan_dan_armada(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $kapal = Kapal::factory()->create([
            'nama_kapal' => 'Speedboat Bahari',
            'status' => Kapal::STATUS_SEDANG_BERLAYAR,
            'waktu_mulai_trip' => now()->subMinutes(15),
        ]);

        Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'nama_pembeli' => 'Hasan Basri',
            'status' => Tiket::STATUS_SELESAI,
            'tanggal_scan' => now(),
            'tanggal_pemesanan' => now(),
        ]);

        $response = $this->actingAs($operator)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Operator Dermaga');
        $response->assertSee('Tiket Di-Scan Hari Ini');
        $response->assertSee('Kesiapan Armada Kapal Dermaga');
        $response->assertSee('Speedboat Bahari');
        $response->assertSee('Hasan Basri');
        $response->assertSee('Scanner QR Dermaga');
        // Operator tidak melihat card omset/pendapatan rupiah
        $response->assertDontSee('Omset Bulan Ini');
        $response->assertDontSee('Pendapatan Bulan Ini');
    }
}
