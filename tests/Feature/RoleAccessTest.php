<?php

namespace Tests\Feature;

use App\Models\Tiket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_dapat_mengakses_semua_menu(): void
    {
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);

        $this->actingAs($superadmin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.kapal.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.tiket.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.scan.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.laporan.index'))->assertOk();
    }

    public function test_kasir_dapat_mengakses_dashboard_pemesanan_dan_laporan(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($kasir)->get(route('admin.tiket.index'))->assertOk();
        $this->actingAs($kasir)->get(route('admin.laporan.index'))->assertOk();
    }

    public function test_kasir_dilarang_mengakses_kapal_users_dan_scan(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->get(route('admin.kapal.index'))->assertForbidden();
        $this->actingAs($kasir)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($kasir)->get(route('admin.scan.index'))->assertForbidden();
    }

    public function test_operator_dapat_mengakses_dashboard_dan_scan_saja(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);

        $this->actingAs($operator)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($operator)->get(route('admin.scan.index'))->assertOk();
    }

    public function test_operator_dilarang_mengakses_kapal_tiket_users_dan_laporan(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);

        $this->actingAs($operator)->get(route('admin.kapal.index'))->assertForbidden();
        $this->actingAs($operator)->get(route('admin.tiket.index'))->assertForbidden();
        $this->actingAs($operator)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($operator)->get(route('admin.laporan.index'))->assertForbidden();
    }

    public function test_sidebar_menampilkan_menu_sesuai_role(): void
    {
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);

        // Superadmin melihat semua menu di layout
        $respSuperadmin = $this->actingAs($superadmin)->get(route('admin.dashboard'));
        $respSuperadmin->assertSee('Kapal');
        $respSuperadmin->assertSee('Pemesanan');
        $respSuperadmin->assertSee('Scan Tiket');
        $respSuperadmin->assertSee('Users');
        $respSuperadmin->assertSee('Laporan');

        // Kasir hanya melihat Dashboard, Pemesanan, Laporan
        $respKasir = $this->actingAs($kasir)->get(route('admin.dashboard'));
        $respKasir->assertSee('Pemesanan');
        $respKasir->assertSee('Laporan');
        $respKasir->assertDontSee(route('admin.kapal.index'));
        $respKasir->assertDontSee(route('admin.users.index'));
        $respKasir->assertDontSee(route('admin.scan.index'));

        // Operator hanya melihat Dashboard dan Scan Tiket
        $respOperator = $this->actingAs($operator)->get(route('admin.dashboard'));
        $respOperator->assertSee('Scan Tiket');
        $respOperator->assertDontSee(route('admin.kapal.index'));
        $respOperator->assertDontSee(route('admin.tiket.index'));
        $respOperator->assertDontSee(route('admin.users.index'));
        $respOperator->assertDontSee(route('admin.laporan.index'));
    }
}
