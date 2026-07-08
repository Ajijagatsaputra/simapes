<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_product_with_image()
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        $file = UploadedFile::fake()->create('seragam.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($admin)
            ->post(route('admin.produk.store'), [
                'nama_produk' => 'Baju Seragam Baru',
                'jenis_seragam' => 'Umum',
                'harga' => 120000,
                'deskripsi' => 'Deskripsi seragam baru',
                'stok' => 50,
                'gambar' => $file,
            ]);

        $response->assertRedirect(route('admin.produk.index'));
        $response->assertSessionHas('success');

        $produk = Produk::first();
        $this->assertNotNull($produk->gambar);
        $this->assertFileExists(public_path($produk->gambar));

        // Clean up the uploaded file
        if (file_exists(public_path($produk->gambar))) {
            @unlink(public_path($produk->gambar));
        }
    }

    public function test_admin_can_update_product_and_replace_image()
    {
        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        $produk = Produk::create([
            'nama_produk' => 'Baju Seragam Lama',
            'jenis_seragam' => 'Umum',
            'harga' => 100000,
            'deskripsi' => 'Deskripsi seragam lama',
            'stok' => 30,
        ]);

        // First upload
        $file1 = UploadedFile::fake()->create('lama.jpg', 100, 'image/jpeg');
        $this->actingAs($admin)
            ->put(route('admin.produk.update', $produk->id), [
                'nama_produk' => 'Baju Seragam Edit',
                'jenis_seragam' => 'Umum',
                'harga' => 110000,
                'deskripsi' => 'Deskripsi seragam edit',
                'stok' => 35,
                'gambar' => $file1,
            ]);

        $produk->refresh();
        $oldImagePath = public_path($produk->gambar);
        $this->assertFileExists($oldImagePath);

        // Second upload
        $file2 = UploadedFile::fake()->create('baru.jpg', 100, 'image/jpeg');
        $this->actingAs($admin)
            ->put(route('admin.produk.update', $produk->id), [
                'nama_produk' => 'Baju Seragam Edit 2',
                'jenis_seragam' => 'Umum',
                'harga' => 115000,
                'deskripsi' => 'Deskripsi seragam edit 2',
                'stok' => 40,
                'gambar' => $file2,
            ]);

        $produk->refresh();
        $newImagePath = public_path($produk->gambar);
        $this->assertFileExists($newImagePath);
        $this->assertFileDoesNotExist($oldImagePath); // Old image should be deleted

        // Clean up
        if (file_exists($newImagePath)) {
            @unlink($newImagePath);
        }
    }

    public function test_admin_can_delete_product_and_image_is_removed()
    {
        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        $file = UploadedFile::fake()->create('to_delete.jpg', 100, 'image/jpeg');

        $this->actingAs($admin)
            ->post(route('admin.produk.store'), [
                'nama_produk' => 'Baju Seragam Hapus',
                'jenis_seragam' => 'Umum',
                'harga' => 120000,
                'deskripsi' => 'Deskripsi seragam hapus',
                'stok' => 50,
                'gambar' => $file,
            ]);

        $produk = Produk::where('nama_produk', 'Baju Seragam Hapus')->first();
        $imagePath = public_path($produk->gambar);
        $this->assertFileExists($imagePath);

        $this->actingAs($admin)
            ->delete(route('admin.produk.destroy', $produk->id));

        $this->assertDatabaseMissing('produks', ['id' => $produk->id]);
        $this->assertFileDoesNotExist($imagePath);
    }
}
