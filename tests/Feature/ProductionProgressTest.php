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

    public function test_order_status_change_to_dikerjakan_auto_initializes_default_progress_stage(): void
    {
        $pesanan = $this->createOrder('diproses');

        // Verify no progress records exist initially
        $this->assertCount(0, $pesanan->progresProduksis);

        // Update status to dikerjakan
        $response = $this
            ->actingAs($this->admin)
            ->patch(route('admin.pesanan.updateStatus', $pesanan->id), [
                'status' => 'dikerjakan',
            ]);

        $response->assertRedirect();
        $this->assertEquals('dikerjakan', $pesanan->fresh()->status);

        // Verify default progress record is created
        $progress = $pesanan->fresh()->progresProduksis;
        $this->assertCount(1, $progress);
        $this->assertEquals('Persiapan Bahan', $progress->first()->tahapan);
        $this->assertEquals(50, $progress->first()->jumlah_pcs);
    }

    public function test_admin_can_access_progress_management_page(): void
    {
        $pesanan = $this->createOrder('dikerjakan');

        // Seed default progress
        ProgresProduksi::create([
            'pesanan_id' => $pesanan->id,
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

        // Total items is 50. If we submit stages sum equal to 45 or 55, it should fail validation.
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.pesanan.progres.update', $pesanan->id), [
                'stages' => [
                    [
                        'tahapan' => 'Persiapan Bahan',
                        'jumlah_pcs' => 20,
                        'catatan' => 'Sebagian bahan siap',
                    ],
                    [
                        'tahapan' => 'Proses Jahit',
                        'jumlah_pcs' => 25, // Sum is 45, which is not 50
                        'catatan' => 'Mulai dijahit',
                    ]
                ]
            ]);

        $response->assertSessionHasErrors(['stages']);
        $this->assertCount(0, $pesanan->fresh()->progresProduksis); // Should not have saved
    }

    public function test_admin_can_submit_valid_progress_stages_quantity_and_upload_documentation(): void
    {
        Storage::fake('public');
        $pesanan = $this->createOrder('dikerjakan');

        $fakeImage = UploadedFile::fake()->create('proses_jahit.jpg', 100, 'image/jpeg');

        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.pesanan.progres.update', $pesanan->id), [
                'stages' => [
                    [
                        'tahapan' => 'Persiapan Bahan',
                        'jumlah_pcs' => 20,
                        'catatan' => 'Bahan katun siap',
                    ],
                    [
                        'tahapan' => 'Proses Jahit',
                        'jumlah_pcs' => 30, // Sum is 50, which matches total items
                        'catatan' => 'Jahit bagian lengan',
                        'dokumentasi' => $fakeImage,
                    ]
                ]
            ]);

        $response->assertRedirect(route('admin.pesanan.index'));
        $response->assertSessionHas('success');

        $progress = $pesanan->fresh()->progresProduksis()->orderBy('id')->get();
        $this->assertCount(2, $progress);

        $this->assertEquals('Persiapan Bahan', $progress[0]->tahapan);
        $this->assertEquals(20, $progress[0]->jumlah_pcs);
        $this->assertNull($progress[0]->dokumentasi);

        $this->assertEquals('Proses Jahit', $progress[1]->tahapan);
        $this->assertEquals(30, $progress[1]->jumlah_pcs);
        $this->assertNotNull($progress[1]->dokumentasi);

        // Verify storage upload
        Storage::disk('public')->assertExists($progress[1]->dokumentasi);
    }

    public function test_customer_can_view_production_progress_on_dashboard_and_detail_page(): void
    {
        $pesanan = $this->createOrder('dikerjakan');

        $progress1 = ProgresProduksi::create([
            'pesanan_id' => $pesanan->id,
            'tahapan' => 'Persiapan Bahan',
            'jumlah_pcs' => 20,
            'catatan' => 'Bahan siap',
        ]);

        $progress2 = ProgresProduksi::create([
            'pesanan_id' => $pesanan->id,
            'tahapan' => 'Proses Potong',
            'jumlah_pcs' => 30,
            'catatan' => 'Potong kain',
        ]);

        // 1. Dashboard View
        $responseDashboard = $this
            ->actingAs($this->customer)
            ->get(route('pelanggan.dashboard'));

        $responseDashboard->assertOk();
        $responseDashboard->assertSee('Transparansi Progres Produksi Aktif (Real-time)');
        $responseDashboard->assertSee('ORD-TEST-001');
        $responseDashboard->assertSee('Persiapan Bahan');
        $responseDashboard->assertSee('20');
        $responseDashboard->assertSee('Pcs');
        $responseDashboard->assertSee('Proses Potong');
        $responseDashboard->assertSee('30');

        // 2. Order Detail View
        $responseDetail = $this
            ->actingAs($this->customer)
            ->get(route('pelanggan.pesanan.show', $pesanan->id));

        $responseDetail->assertOk();
        $responseDetail->assertSee('Progres Produksi Seragam');
        $responseDetail->assertSee('Persiapan Bahan');
        $responseDetail->assertSee('20');
        $responseDetail->assertSee('Proses Potong');
        $responseDetail->assertSee('30');
    }
}
