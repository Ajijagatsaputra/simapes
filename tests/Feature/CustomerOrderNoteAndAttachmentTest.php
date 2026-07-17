<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\DetailPesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerOrderNoteAndAttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_order_with_notes_and_images(): void
    {
        Storage::fake('public');

        $customer = User::factory()->create(['role' => 'pelanggan']);
        $produk = Produk::create([
            'nama_produk' => 'Kaos Olahraga',
            'jenis_seragam' => 'Atasan',
            'harga' => 10000,
            'stok' => 100,
            'deskripsi' => 'Kaos'
        ]);

        $file = UploadedFile::fake()->image('desain.png');

        $response = $this
            ->actingAs($customer)
            ->post(route('pelanggan.pesanan.store'), [
                'items' => [
                    [
                        'produk_id' => $produk->id,
                        'ukuran' => 'M',
                        'total_item' => 5,
                        'catatan' => 'Bordir nama sekolah di belakang',
                        'gambar' => $file,
                    ]
                ]
            ]);

        $pesanan = Pesanan::first();
        $this->assertNotNull($pesanan);

        $detail = DetailPesanan::first();
        $this->assertNotNull($detail);
        $this->assertEquals('Bordir nama sekolah di belakang', $detail->catatan);
        $this->assertNotNull($detail->path_gambar);

        Storage::disk('public')->assertExists($detail->path_gambar);
    }

    public function test_order_fails_with_invalid_image_format(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);
        $produk = Produk::create([
            'nama_produk' => 'Kaos Olahraga',
            'jenis_seragam' => 'Atasan',
            'harga' => 10000,
            'stok' => 100,
            'deskripsi' => 'Kaos'
        ]);

        $file = UploadedFile::fake()->create('desain.pdf', 100, 'application/pdf');

        $response = $this
            ->actingAs($customer)
            ->from(route('pelanggan.pesanan.create'))
            ->post(route('pelanggan.pesanan.store'), [
                'items' => [
                    [
                        'produk_id' => $produk->id,
                        'ukuran' => 'M',
                        'total_item' => 5,
                        'catatan' => 'Catatan',
                        'gambar' => $file,
                    ]
                ]
            ]);

        $response->assertSessionHasErrors(['items.0.gambar']);
        $this->assertEquals(0, Pesanan::count());
    }

    public function test_order_fails_when_image_exceeds_5mb(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);
        $produk = Produk::create([
            'nama_produk' => 'Kaos Olahraga',
            'jenis_seragam' => 'Atasan',
            'harga' => 10000,
            'stok' => 100,
            'deskripsi' => 'Kaos'
        ]);

        // 6000 KB is ~5.8 MB
        $file = UploadedFile::fake()->create('desain_besar.png', 6000, 'image/png');

        $response = $this
            ->actingAs($customer)
            ->from(route('pelanggan.pesanan.create'))
            ->post(route('pelanggan.pesanan.store'), [
                'items' => [
                    [
                        'produk_id' => $produk->id,
                        'ukuran' => 'M',
                        'total_item' => 5,
                        'catatan' => 'Catatan',
                        'gambar' => $file,
                    ]
                ]
            ]);

        $response->assertSessionHasErrors(['items.0.gambar']);
        $this->assertEquals(0, Pesanan::count());
    }

    public function test_csv_template_includes_catatan_header(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);

        $response = $this
            ->actingAs($customer)
            ->get(route('pelanggan.pesanan.template'));

        $response->assertOk();
        $this->assertStringContainsString('Catatan', $response->getContent());
        $this->assertStringContainsString('Bordir logo OSIS di lengan kanan', $response->getContent());
    }

    public function test_excel_upload_parses_catatan(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);
        $produk = Produk::create([
            'nama_produk' => 'Kaos Olahraga',
            'jenis_seragam' => 'Atasan',
            'harga' => 10000,
            'stok' => 100,
            'deskripsi' => 'Kaos'
        ]);

        $csvContent = "\xEF\xBB\xBF" . "TEMPLATE PEMESANAN MASSAL SERAGAM\n"
            . "Petunjuk\n"
            . "Ukuran yang valid\n"
            . "\n"
            . "No,Nama_Produk,Ukuran,Jumlah,Catatan\n"
            . "1,Kaos Olahraga,M,10,Bordir khusus di belakang\n";

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this
            ->actingAs($customer)
            ->postJson(route('pelanggan.pesanan.upload'), [
                'file_excel' => $file
            ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('items.0.catatan', 'Bordir khusus di belakang');
        $response->assertJsonPath('items.0.jumlah', 10);
        $response->assertJsonPath('items.0.ukuran', 'M');
    }
}
