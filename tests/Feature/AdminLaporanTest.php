<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $customer1;
    protected $customer2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create two customer users with different schools
        $this->customer1 = User::factory()->create([
            'role' => 'pelanggan',
            'nama_sekolah' => 'SMA 1 Kramat',
        ]);

        $this->customer2 = User::factory()->create([
            'role' => 'pelanggan',
            'nama_sekolah' => 'SMP 2 Adiwerna',
        ]);

        // Create orders with different statuses and financials
        // Order 1: SMA 1 Kramat, Status: diproses, Total: 1.000.000, Terbayar: 500.000 (Sisa: 500.000 -> Belum Lunas)
        Pesanan::create([
            'user_id' => $this->customer1->id,
            'no_pesanan' => 'ORD-001',
            'tanggal_pesanan' => now(),
            'status' => 'diproses',
            'total_harga' => 1000000,
            'total_terbayar' => 500000,
            'sisa_tagihan' => 500000,
        ]);

        // Order 2: SMP 2 Adiwerna, Status: selesai, Total: 2.000.000, Terbayar: 2.000.000 (Sisa: 0 -> Lunas)
        Pesanan::create([
            'user_id' => $this->customer2->id,
            'no_pesanan' => 'ORD-002',
            'tanggal_pesanan' => now(),
            'status' => 'selesai',
            'total_harga' => 2000000,
            'total_terbayar' => 2000000,
            'sisa_tagihan' => 0,
        ]);
    }

    public function test_admin_can_view_laporan_index_with_filters()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Pemesanan');
        $response->assertSee('Filter Laporan');
        $response->assertSee('SMA 1 Kramat');
        $response->assertSee('SMP 2 Adiwerna');
        $response->assertSee('ORD-001');
        $response->assertSee('ORD-002');
    }

    public function test_admin_can_filter_laporan_by_school()
    {
        // Filter specifically for SMA 1 Kramat
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.index', [
                'sekolah' => 'SMA 1 Kramat'
            ]));

        $response->assertStatus(200);
        $response->assertSee('ORD-001');
        $response->assertDontSee('ORD-002');
    }

    public function test_admin_can_filter_laporan_by_progress_kerja()
    {
        // Filter for sedang_berjalan
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.index', [
                'progress' => 'sedang_berjalan'
            ]));

        $response->assertStatus(200);
        $response->assertSee('ORD-001');
        $response->assertDontSee('ORD-002');

        // Filter for selesai
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.index', [
                'progress' => 'selesai'
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('ORD-001');
        $response->assertSee('ORD-002');
    }

    public function test_admin_can_filter_laporan_by_keuangan_status()
    {
        // Filter for belum_lunas (has sisa tagihan)
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.index', [
                'keuangan' => 'belum_lunas'
            ]));

        $response->assertStatus(200);
        $response->assertSee('ORD-001');
        $response->assertDontSee('ORD-002');

        // Filter for lunas
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.index', [
                'keuangan' => 'lunas'
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('ORD-001');
        $response->assertSee('ORD-002');
    }

    public function test_admin_can_view_cetak_laporan()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.cetak', [
                'sekolah' => 'SMA 1 Kramat'
            ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Transaksi Pemesanan');
        $response->assertSee('ORD-001');
        $response->assertDontSee('ORD-002');
    }

    public function test_admin_can_export_laporan_excel()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.excel', [
                'keuangan' => 'lunas'
            ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $response->assertSee('ORD-002');
        $response->assertDontSee('ORD-001');
    }
}
