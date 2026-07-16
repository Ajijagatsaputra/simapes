<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CustomerPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_initiate_xendit_payment_and_auto_allocate()
    {
        // Mock Xendit API response
        Http::fake([
            'https://api.xendit.co/v2/invoices' => Http::response([
                'id' => 'xend-inv-test-123',
                'invoice_url' => 'https://checkout.xendit.co/web/xend-inv-test-123',
                'status' => 'PENDING',
            ], 200)
        ]);

        // 1. Setup customer, product, and order
        $customer = User::factory()->create(['role' => 'pelanggan', 'email' => 'customer@example.com']);
        $produk1 = Produk::create([
            'nama_produk' => 'Baju Seragam A',
            'jenis_seragam' => 'Umum',
            'harga' => 100000,
            'deskripsi' => 'Seragam A',
            'stok' => 50,
        ]);
        $produk2 = Produk::create([
            'nama_produk' => 'Baju Seragam B',
            'jenis_seragam' => 'Umum',
            'harga' => 200000,
            'deskripsi' => 'Seragam B',
            'stok' => 50,
        ]);

        $pesanan = Pesanan::create([
            'user_id' => $customer->id,
            'no_pesanan' => 'ORD-TEST-01',
            'tanggal_pesanan' => now()->toDateString(),
            'total_harga' => 500000,
            'sisa_tagihan' => 500000,
            'status' => 'pending',
            'status_pembayaran' => 'belum_bayar',
        ]);

        $detail1 = DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk1->id,
            'ukuran' => 'M',
            'harga_satuan' => 100000,
            'total_item' => 3, // Subtotal Rp 300,000
            'subtotal' => 300000,
        ]);

        $detail2 = DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk2->id,
            'ukuran' => 'L',
            'harga_satuan' => 200000,
            'total_item' => 1, // Subtotal Rp 200,000
            'subtotal' => 200000,
        ]);

        // 2. Submit payment request as customer (redirect to Xendit)
        $response = $this->actingAs($customer)
            ->post(route('pelanggan.pesanan.bayar', $pesanan->id), [
                'catatan_pelanggan' => 'Mau bayar DP via Xendit',
                'termin_ke' => '1',
            ]);

        $response->assertRedirect('https://checkout.xendit.co/web/xend-inv-test-123');

        // 3. Assert database records (payment pending)
        $this->assertDatabaseHas('pembayarans', [
            'pesanan_id' => $pesanan->id,
            'termin_ke' => 1,
            'jumlah_bayar' => 250000,
            'metode_pembayaran' => 'xendit',
            'catatan_pelanggan' => 'Mau bayar DP via Xendit',
            'status' => 'pending',
            'xendit_invoice_id' => 'xend-inv-test-123',
            'xendit_invoice_url' => 'https://checkout.xendit.co/web/xend-inv-test-123',
        ]);

        $pembayaran = Pembayaran::first();

        $this->assertDatabaseHas('pembayaran_details', [
            'pembayaran_id' => $pembayaran->id,
            'detail_pesanan_id' => $detail1->id,
            'jumlah_cover' => 2,
        ]);

        // 4. Test Webhook callback from Xendit
        $webhookResponse = $this->postJson(route('webhook.xendit'), [
            'external_id' => 'simapes-payment-' . $pembayaran->id,
            'status' => 'PAID',
            'payment_method' => 'EWALLET',
        ]);

        $webhookResponse->assertStatus(200);

        // Assert payment is now verified via webhook
        $this->assertDatabaseHas('pembayarans', [
            'id' => $pembayaran->id,
            'status' => 'verified',
            'metode_pembayaran' => 'ewallet',
        ]);

        // Assert recalculated order totals
        $pesanan->refresh();
        $this->assertEquals(250000, $pesanan->total_terbayar);
        $this->assertEquals(250000, $pesanan->sisa_tagihan);
        $this->assertEquals('dp', $pesanan->status_pembayaran);

        // Assert recalculated item coverage
        $detail1->refresh();
        $detail2->refresh();
        $this->assertEquals(2, $detail1->jumlah_terbayar);
        $this->assertEquals(0, $detail2->jumlah_terbayar);
    }
}
