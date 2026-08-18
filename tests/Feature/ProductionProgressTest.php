<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\ProgresProduksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductionProgressTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'pelanggan',
            'nama_sekolah' => 'SMAN 1 Test',
        ]);

        $this->produk = Produk::create([
            'nama_produk' => 'Baju Seragam OSIS SMA',
            'jenis_seragam' => 'SMA/SMK',
            'harga' => 85000,
            'stok' => 100,
            'deskripsi' => 'Bahan katun premium'
        ]);
    }

    private function createOrder(string $status, int $totalQty = 50): Pesanan
    {
        $pesanan = Pesanan::create([
            'no_pesanan' => 'ORD-TEST-001',
            'user_id' => $this->customer->id,
            'total_harga' => $totalQty * 85000,
            'total_terbayar' => ($totalQty * 85000) * 0.5,
            'sisa_tagihan' => ($totalQty * 85000) * 0.5,
            'status_pembayaran' => 'dp',
            'tanggal_pesanan' => now(),
            'status' => $status,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $this->produk->id,
            'ukuran' => 'L',
            'harga_satuan' => 85000,
            'total_item' => $totalQty,
            'jumlah_terbayar' => 0,
            'subtotal' => $totalQty * 85000,
        ]);

        return $pesanan;
    }

    public function test_admin_can_access_progress_management_page(): void
    {
        $pesanan = $this->createOrder('dikerjakan');

        // Seed default progress Tahap 1
        ProgresProduksi::create([
            'pesanan_id' => $pesanan->id,
            'tahapan_ke' => 1,
            'tahapan' => 'Persiapan Bahan',
            'jumlah_pcs' => 50,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.pesanan.progres', $pesanan->id));

        $response->assertOk();
        $response->assertSee('Kelola Progres Produksi');
        $response->assertSee('ORD-TEST-001');
        $response->assertSee('Persiapan Bahan');
    }

    public function test_admin_cannot_submit_invalid_progress_stages_quantity(): void
    {
        $pesanan = $this->createOrder('dikerjakan');

        // Total items is 50. If we submit 51 pcs for Tahap 1, it should fail validation.
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.pesanan.progres.update', $pesanan->id), [
                'tahapan_ke' => 1,
                'jumlah_pcs' => 51,
                'catatan' => 'Bahan siap',
            ]);

        $response->assertSessionHasErrors(['jumlah_pcs']);
    }

    public function test_admin_can_submit_valid_progress_stages_quantity_and_upload_documentation(): void
    {
        Storage::fake('public');
        $pesanan = $this->createOrder('dikerjakan');

        $fakeImage = UploadedFile::fake()->create('proses_bahan.jpg', 100, 'image/jpeg');

        // 1. Submit Tahap 1 (Persiapan Bahan)
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.pesanan.progres.update', $pesanan->id), [
                'tahapan_ke' => 1,
                'jumlah_pcs' => 50,
                'catatan' => 'Bahan katun siap',
                'dokumentasi' => $fakeImage,
                'tandai_selesai' => 1,
            ]);

        $response->assertRedirect(route('admin.pesanan.progres', $pesanan->id));
        $response->assertSessionHas('success');

        $progress1 = ProgresProduksi::where('pesanan_id', $pesanan->id)->where('tahapan_ke', 1)->first();
        $this->assertNotNull($progress1);
        $this->assertEquals('Persiapan Bahan', $progress1->tahapan);
        $this->assertEquals(50, $progress1->jumlah_pcs);
        $this->assertNotNull($progress1->dokumentasi);
        $this->assertNotNull($progress1->selesai_pada);
        Storage::disk('public')->assertExists($progress1->dokumentasi);
    }

    public function test_stage_sequence_is_enforced(): void
    {
        $pesanan = $this->createOrder('dikerjakan');

        // Try submitting Tahap 2 without Tahap 1 completed -> should fail with error message
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.pesanan.progres.update', $pesanan->id), [
                'tahapan_ke' => 2,
                'jumlah_pcs' => 50,
                'catatan' => 'Coba potong',
                'tandai_selesai' => 1,
            ]);

        $response->assertSessionHas('error');
        $this->assertNull(ProgresProduksi::where('pesanan_id', $pesanan->id)->where('tahapan_ke', 2)->first());
    }

    public function test_customer_can_view_production_progress_on_dashboard_and_detail_page(): void
    {
        $pesanan = $this->createOrder('dikerjakan');

        ProgresProduksi::create([
            'pesanan_id' => $pesanan->id,
            'tahapan_ke' => 1,
            'tahapan' => 'Persiapan Bahan',
            'jumlah_pcs' => 20,
            'catatan' => 'Bahan siap',
            'selesai_pada' => now(),
        ]);

        // 1. Dashboard View
        $responseDashboard = $this
            ->actingAs($this->customer)
            ->get(route('pelanggan.dashboard'));

        $responseDashboard->assertOk();
        $responseDashboard->assertSee('Transparansi Progres Produksi Aktif (Real-time)');
        $responseDashboard->assertSee('ORD-TEST-001');

        // 2. Order Detail View
        $responseDetail = $this
            ->actingAs($this->customer)
            ->get(route('pelanggan.pesanan.show', $pesanan->id));

        $responseDetail->assertOk();
        $responseDetail->assertSee('Progres');
        $responseDetail->assertSee('Produksi');
        $responseDetail->assertSee('Seragam');
        $responseDetail->assertSee('Persiapan Bahan');
    }
}
