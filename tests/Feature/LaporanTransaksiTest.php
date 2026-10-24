<?php

namespace Tests\Feature;

use App\Models\Kapal;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTransaksiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dapat_mengakses_halaman_laporan_bulanan(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $kapal = Kapal::factory()->create();
        Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'tanggal_pemesanan' => now(),
            'status' => Tiket::STATUS_SELESAI,
        ]);

        $response = $this->actingAs($user)->get(route('admin.laporan.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Transaksi');
        $response->assertSee('Rincian Transaksi');
    }

    public function test_filter_laporan_berdasarkan_kapal_dan_status(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $kapalA = Kapal::factory()->create(['nama_kapal' => 'Speedboat A']);
        $kapalB = Kapal::factory()->create(['nama_kapal' => 'Speedboat B']);

        $tiketA = Tiket::factory()->create([
            'kapal_id' => $kapalA->id,
            'nama_pembeli' => 'Pembeli Kapal A',
            'tanggal_pemesanan' => now(),
            'status' => Tiket::STATUS_SELESAI,
        ]);

        $tiketB = Tiket::factory()->create([
            'kapal_id' => $kapalB->id,
            'nama_pembeli' => 'Pembeli Kapal B',
            'tanggal_pemesanan' => now(),
            'status' => Tiket::STATUS_BATAL,
        ]);

        // Filter kapal A saja
        $response = $this->actingAs($user)->get(route('admin.laporan.index', [
            'kapal_id' => $kapalA->id,
        ]));
        $response->assertStatus(200);
        $response->assertSee('Pembeli Kapal A');
        $response->assertDontSee('Pembeli Kapal B');

        // Filter status Dibatalkan (-1) saja
        $responseStatus = $this->actingAs($user)->get(route('admin.laporan.index', [
            'status' => Tiket::STATUS_BATAL,
        ]));
        $responseStatus->assertStatus(200);
        $responseStatus->assertSee('Pembeli Kapal B');
        $responseStatus->assertDontSee('Pembeli Kapal A');
    }

    public function test_export_laporan_menghasilkan_file_excel_terformat(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $kapal = Kapal::factory()->create(['nama_kapal' => 'Speedboat C']);
        Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'nama_pembeli' => 'Rina Wijaya',
            'tarif' => 150000,
            'diskon' => 25000,
            'total_bayar' => 125000,
            'tanggal_pemesanan' => now(),
            'status' => Tiket::STATUS_SELESAI,
        ]);

        $response = $this->actingAs($user)->get(route('admin.laporan.export-excel', [
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $this->assertStringContainsString('.xls', $response->headers->get('Content-Disposition') ?? '');
        $this->assertStringContainsString('urn:schemas-microsoft-com:office:spreadsheet', $response->getContent() ?: '');
        $this->assertStringContainsString('LAPORAN TRANSAKSI WAHANA GEDAMBAAN', $response->getContent() ?: '');
        $this->assertStringContainsString('Rina Wijaya', $response->getContent() ?: '');
    }

    public function test_export_laporan_menghasilkan_file_csv(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_KASIR]);
        $kapal = Kapal::factory()->create();
        Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'nama_pembeli' => 'Budi Santoso',
            'tanggal_pemesanan' => now(),
            'status' => Tiket::STATUS_SELESAI,
        ]);

        $response = $this->actingAs($user)->get(route('admin.laporan.export-csv', [
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('.csv', $response->headers->get('Content-Disposition') ?? '');
    }
}
