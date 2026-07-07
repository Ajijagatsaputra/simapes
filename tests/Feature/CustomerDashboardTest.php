<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_dashboard_calculates_and_displays_production_metrics_correctly(): void
    {
        // 1. Create a customer
        $user = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        // 2. Create products
        $produk = Produk::create([
            'nama_produk' => 'Kaos Olahraga',
            'jenis_seragam' => 'Atasan',
            'harga' => 10000,
            'stok' => 100,
            'deskripsi' => 'Kaos'
        ]);

        // 3. Create active order (dikerjakan): 50 pcs size M, paid 20 pcs
        $pesananAktif = Pesanan::create([
            'no_pesanan' => 'ORD-001',
            'user_id' => $user->id,
            'total_harga' => 500000,
            'total_terbayar' => 200000,
            'sisa_tagihan' => 300000,
            'status_pembayaran' => 'dp',
            'tanggal_pesanan' => now(),
            'status' => 'dikerjakan'
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesananAktif->id,
            'produk_id' => $produk->id,
            'ukuran' => 'M',
            'harga_satuan' => 10000,
            'total_item' => 50,
            'jumlah_terbayar' => 20,
            'subtotal' => 500000
        ]);

        // 4. Create completed order: 10 pcs size XL, fully paid
        $pesananSelesai = Pesanan::create([
            'no_pesanan' => 'ORD-002',
            'user_id' => $user->id,
            'total_harga' => 100000,
            'total_terbayar' => 100000,
            'sisa_tagihan' => 0,
            'status_pembayaran' => 'lunas',
            'tanggal_pesanan' => now(),
            'status' => 'selesai'
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesananSelesai->id,
            'produk_id' => $produk->id,
            'ukuran' => 'XL',
            'harga_satuan' => 10000,
            'total_item' => 10,
            'jumlah_terbayar' => 10,
            'subtotal' => 100000
        ]);

        // 5. Request customer dashboard
        $response = $this
            ->actingAs($user)
            ->get(route('pelanggan.dashboard'));

        // 6. Assertions
        $response->assertOk();
        $response->assertViewHas('pcsBelumDikerjakan', 30);
        $response->assertViewHas('pcsSedangDiproses', 20);
        $response->assertViewHas('pcsSelesai', 10);

        $breakdown = $response->viewData('breakdown');
        $this->assertCount(2, $breakdown);

        // Find the Kaos Olahraga size M item
        $mItem = collect($breakdown)->firstWhere('ukuran', 'M');
        $this->assertEquals(50, $mItem['total_pesanan']);
        $this->assertEquals(30, $mItem['belum_dikerjakan']);
        $this->assertEquals(20, $mItem['sedang_diproses']);
        $this->assertEquals(0, $mItem['selesai']);

        // Find the Kaos Olahraga size XL item
        $xlItem = collect($breakdown)->firstWhere('ukuran', 'XL');
        $this->assertEquals(10, $xlItem['total_pesanan']);
        $this->assertEquals(0, $xlItem['belum_dikerjakan']);
        $this->assertEquals(0, $xlItem['sedang_diproses']);
        $this->assertEquals(10, $xlItem['selesai']);

        // Verify HTML contents
        $response->assertSee('Transparansi Status Pengerjaan (Pcs)');
        $response->assertSee('30 Pcs');
        $response->assertSee('20 Pcs');
        $response->assertSee('10 Pcs');

        $response->assertSee('Rincian Pesanan Terkini (Breakdown per Kategori)');
        $response->assertSee('Kaos Olahraga');
        $response->assertSee('M');
        $response->assertSee('XL');
    }
}
