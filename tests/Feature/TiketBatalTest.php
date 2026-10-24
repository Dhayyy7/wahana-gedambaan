<?php

namespace Tests\Feature;

use App\Models\Kapal;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TiketBatalTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_dapat_membatalkan_pesanan_tiket_dan_status_menjadi_minus_satu(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $kapal = Kapal::factory()->create();
        $tiket = Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'status' => Tiket::STATUS_PENDING,
        ]);

        $response = $this->actingAs($kasir)->patch(route('admin.tiket.cancel', $tiket));

        $response->assertSessionHas('success');
        $this->assertEquals(Tiket::STATUS_BATAL, $tiket->fresh()->status);
        $this->assertTrue($tiket->fresh()->isDibatalkan());
    }

    public function test_kasir_tidak_bisa_membatalkan_tiket_yang_sudah_digunakan(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $kapal = Kapal::factory()->create();
        $tiket = Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'status' => Tiket::STATUS_SELESAI,
            'tanggal_scan' => now(),
        ]);

        $response = $this->actingAs($kasir)->patch(route('admin.tiket.cancel', $tiket));

        $response->assertSessionHasErrors('error');
        $this->assertEquals(Tiket::STATUS_SELESAI, $tiket->fresh()->status);
    }

    public function test_kasir_tidak_bisa_menghapus_transaksi_tiket(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $kapal = Kapal::factory()->create();
        $tiket = Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'status' => Tiket::STATUS_PENDING,
        ]);

        $response = $this->actingAs($kasir)->delete(route('admin.tiket.destroy', $tiket));

        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('tikets', ['id' => $tiket->id]);
    }

    public function test_superadmin_bisa_menghapus_tiket_pending(): void
    {
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $kapal = Kapal::factory()->create();
        $tiket = Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'status' => Tiket::STATUS_PENDING,
        ]);

        $response = $this->actingAs($superadmin)->delete(route('admin.tiket.destroy', $tiket));

        $response->assertRedirect(route('admin.tiket.index'));
        $this->assertDatabaseMissing('tikets', ['id' => $tiket->id]);
    }

    public function test_tiket_yang_dibatalkan_ditolak_saat_di_scan(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $kapal = Kapal::factory()->create(['status' => Kapal::STATUS_TERSEDIA]);
        $tiket = Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'status' => Tiket::STATUS_BATAL,
        ]);

        $response = $this->actingAs($operator)->postJson(route('admin.scan.process'), [
            'qr_code' => $tiket->qr_code,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'code' => 'TICKET_CANCELLED',
        ]);
    }

    public function test_daftar_tiket_memiliki_pagination_10_pencarian_dan_filter_status(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $kapal = Kapal::factory()->create(['nama_kapal' => 'Speedboat Express']);

        // Buat 12 tiket
        Tiket::factory()->count(11)->create([
            'kapal_id' => $kapal->id,
            'status' => Tiket::STATUS_PENDING,
        ]);

        $tiketSpesifik = Tiket::factory()->create([
            'kapal_id' => $kapal->id,
            'nama_pembeli' => 'Pelanggan Unik XYZ',
            'status' => Tiket::STATUS_BATAL,
        ]);

        // Cek pagination 10 items
        $response = $this->actingAs($kasir)->get(route('admin.tiket.index'));
        $response->assertStatus(200);
        $this->assertCount(10, $response->viewData('tikets'));
        $this->assertEquals(12, $response->viewData('tikets')->total());

        // Cek search
        $responseSearch = $this->actingAs($kasir)->get(route('admin.tiket.index', ['search' => 'Unik XYZ']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Pelanggan Unik XYZ');
        $this->assertEquals(1, $responseSearch->viewData('tikets')->total());

        // Cek filter status dibatalkan (-1)
        $responseFilter = $this->actingAs($kasir)->get(route('admin.tiket.index', ['status' => Tiket::STATUS_BATAL]));
        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('Pelanggan Unik XYZ');
        $this->assertEquals(1, $responseFilter->viewData('tikets')->total());
    }
}
