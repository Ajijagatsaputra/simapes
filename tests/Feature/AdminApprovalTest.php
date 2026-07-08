<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_order_as_pending(): void
    {
        $customer = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $produk = Produk::create([
            'nama_produk' => 'Kaos Olahraga',
            'jenis_seragam' => 'Atasan',
            'harga' => 10000,
            'stok' => 100,
            'deskripsi' => 'Kaos'
        ]);

        $response = $this
            ->actingAs($customer)
            ->post(route('pelanggan.pesanan.store'), [
                'items' => [
                    [
                        'produk_id' => $produk->id,
                        'ukuran' => 'M',
                        'total_item' => 5,
                    ]
                ]
            ]);

        $pesanan = Pesanan::first();
        $this->assertNotNull($pesanan);
        $this->assertEquals('pending', $pesanan->status);
        $this->assertEquals($customer->id, $pesanan->user_id);
    }

    public function test_admin_can_see_pending_orders_and_approve_them(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $produk = Produk::create([
            'nama_produk' => 'Kaos Olahraga',
            'jenis_seragam' => 'Atasan',
            'harga' => 10000,
            'stok' => 100,
            'deskripsi' => 'Kaos'
        ]);

        $pesanan = Pesanan::create([
            'no_pesanan' => 'ORD-999',
            'user_id' => $customer->id,
            'total_harga' => 50000,
            'tanggal_pesanan' => now(),
            'status' => 'pending',
        ]);

        // 1. Verify admin can see pending order
        $response = $this
            ->actingAs($admin)
            ->get(route('admin.pesanan.index', ['status' => 'pending']));

        $response->assertOk();
        $response->assertSee('ORD-999');
        $response->assertSee('Menunggu Persetujuan');

        // 2. Verify admin can update status to diproses
        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.pesanan.updateStatus', $pesanan->id), [
                'status' => 'diproses',
            ]);

        $response->assertRedirect(route('admin.pesanan.index'));
        $this->assertEquals('diproses', $pesanan->fresh()->status);
    }
}
