<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_payment_proof_and_auto_allocate()
    {
        Storage::fake('public');

        // 1. Setup customer, product, and order
        $customer = User::factory()->create(['role' => 'pelanggan']);
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
            'total_harga' => 500000, // (2 * 100k) + (1.5 * 200k)? Let's make it match:
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

        // Total order = 300k + 200k = 500k. Termin 1 (DP 50%) = 250k.
        // Alokasi proporsional termin 1 (nominal = 250k):
        // Detail 1: proporsi = 300k/500k = 0.6. Nominal alokasi = 250k * 0.6 = 150k.
        //           pcs cover = floor(150k / 100k) = 1 pcs. (Selisih = 50k)
        // Detail 2: proporsi = 200k/500k = 0.4. Nominal alokasi = 250k * 0.4 = 100k.
        //           pcs cover = floor(100k / 200k) = 0 pcs. (Selisih = 100k)
        // Total nominal dialokasikan = (1 * 100k) + (0 * 200k) = 100k.
        // Selisih total = 250k - 100k = 150k.
        // Koreksi rounding mendistribusikan sisa ke detail 1:
        // Detail 1: sisa item = 3 - 1 = 2 pcs. Selisih (150k) >= harga_satuan (100k).
        //           tambah = min(floor(150k/100k), 2) = 1 pcs.
        //           Alokasi detail 1 = 1 + 1 = 2 pcs.
        //           Sisa selisih = 150k - 100k = 50k.
        // Detail 2: sisa item = 1 - 0 = 1 pcs. Selisih (50k) < harga_satuan (200k).
        // Hasil alokasi: Detail 1 = 2 pcs, Detail 2 = 0 pcs.

        $file = UploadedFile::fake()->image('bukti_bayar.png');

        // 2. Submit payment as customer
        $response = $this->actingAs($customer)
            ->post(route('pelanggan.pesanan.bayar', $pesanan->id), [
                'metode_pembayaran' => 'qris',
                'bukti_bayar' => $file,
                'catatan_pelanggan' => 'Sudah bayar DP ya min',
                'termin_ke' => '1',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // 3. Assert database records
        $this->assertDatabaseHas('pembayarans', [
            'pesanan_id' => $pesanan->id,
            'termin_ke' => 1,
            'jumlah_bayar' => 250000,
            'metode_pembayaran' => 'qris',
            'catatan_pelanggan' => 'Sudah bayar DP ya min',
            'status' => 'pending',
        ]);

        $pembayaran = Pembayaran::first();
        Storage::disk('public')->assertExists($pembayaran->bukti_bayar);

        $this->assertDatabaseHas('pembayaran_details', [
            'pembayaran_id' => $pembayaran->id,
            'detail_pesanan_id' => $detail1->id,
            'jumlah_cover' => 2,
        ]);

        $this->assertDatabaseMissing('pembayaran_details', [
            'pembayaran_id' => $pembayaran->id,
            'detail_pesanan_id' => $detail2->id,
        ]);

        // 4. Test admin verification
        $admin = User::factory()->create(['role' => 'admin']);

        $verifyResponse = $this->actingAs($admin)
            ->post(route('admin.pesanan.pembayaran.verifikasi', [$pesanan->id, $pembayaran->id]));

        $verifyResponse->assertStatus(302);
        $verifyResponse->assertSessionHas('success');

        // Assert status is now verified
        $this->assertDatabaseHas('pembayarans', [
            'id' => $pembayaran->id,
            'status' => 'verified',
            'verified_by' => $admin->id,
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
