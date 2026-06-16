<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'nama_supplier' => 'Toko Kain Nusantara',
                'no_whatsapp' => '081234567890',
                'alamat' => 'Jl. Tekstil Indah No. 12, Bandung',
                'kategori_bahan' => ['kain'],
                'deskripsi' => 'Mensuplai kain katun prima, kain drill super, kain TC, dan kain furing berkualitas tinggi.',
            ],
            [
                'nama_supplier' => 'Aksesoris Seragam Jaya',
                'no_whatsapp' => '085711223344',
                'alamat' => 'Pasar Tanah Abang Blok A No. 45, Jakarta Pusat',
                'kategori_bahan' => ['kancing', 'benang', 'resleting'],
                'deskripsi' => 'Mensuplai segala aksesoris konveksi: benang jahit polyester, kancing seragam OSIS/Pramuka, dan resleting celana.',
            ],
            [
                'nama_supplier' => 'CV Tekstil Sukses Sejahtera',
                'no_whatsapp' => '081399887766',
                'alamat' => 'Kawasan Industri Cigondewah No. 88, Bandung',
                'kategori_bahan' => ['kain'],
                'deskripsi' => 'Spesialis kain drill, katun oxford, dan kain seragam sekolah terlengkap dengan harga grosir.',
            ],
            [
                'nama_supplier' => 'PD Kancing Sentosa',
                'no_whatsapp' => '082155667788',
                'alamat' => 'Jl. Perniagaan Raya No. 10, Jakarta Barat',
                'kategori_bahan' => ['kancing'],
                'deskripsi' => 'Pusat kancing baju, kancing jas almamater logam, kancing jepret, dan kancing custom logo.',
            ],
            [
                'nama_supplier' => 'Benang & Resleting Abadi',
                'no_whatsapp' => '087844556677',
                'alamat' => 'Jl. Solo-Yogyakarta Km. 15, Klaten',
                'kategori_bahan' => ['benang', 'resleting'],
                'deskripsi' => 'Supplier benang jahit Astra, benang obras, resleting YKK asli, dan resleting koil gulungan.',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['nama_supplier' => $supplier['nama_supplier']],
                $supplier
            );
        }
    }
}
