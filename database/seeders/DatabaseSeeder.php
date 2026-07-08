<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin SIMAPES',
                'role' => 'admin',
                'password' => Hash::make('12345678'),
                'no_whatsapp' => '08123456789',
                'alamat' => 'Kantor SIMAPES Pusat',
                'nama_sekolah' => 'SIMAPES Academy',
            ]
        );

        // 2. Buat Pelanggan Dummy (20 data)
        $pelanggan = [
            [
                'name' => 'Lutfa Nur',
                'role' => 'pelanggan',
                'email' => 'lutfa@gmail.com',
                'password' => Hash::make('12345678'),
                'no_whatsapp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 12',
                'nama_sekolah' => 'SMAN 1 Bandung',
            ],
            [
                'name' => 'Eca Amelia',
                'role' => 'pelanggan',
                'email' => 'eca@gmail.com',
                'password' => Hash::make('12345678'),
                'no_whatsapp' => '085423451212',
                'alamat' => 'Jl. Diponegoro No. 45',
                'nama_sekolah' => 'SMPN 3 Jakarta',
            ]
        ];

        foreach ($pelanggan as $p) {
            User::updateOrCreate(['email' => $p['email']], $p);
        }

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 3; $i <= 20; $i++) {
            User::updateOrCreate(
                ['email' => "pelanggan{$i}@gmail.com"],
                [
                    'name' => $faker->name(),
                    'role' => 'pelanggan',
                    'password' => Hash::make('12345678'),
                    'no_whatsapp' => '08' . $faker->numerify('##########'),
                    'alamat' => $faker->address(),
                    'nama_sekolah' => $faker->randomElement([
                        'SMAN 1 Bandung',
                        'SMAN 3 Jakarta',
                        'SMPN 2 Surabaya',
                        'SMKN 1 Yogyakarta',
                        'SMA Labschool',
                        'SMP Al-Azhar',
                        'SMAN 8 Jakarta',
                        'SMAS Kristen Dago',
                        'SMAN 2 Solo',
                        'SMPN 5 Semarang',
                        'SMAN 1 Bogor',
                        'SMKN 2 Tangerang'
                    ]),
                ]
            );
        }

        // 3. Buat Beberapa Produk Seragam Dummy untuk memudahkan testing
        $produk = [
            [
                'nama_produk' => 'Baju Seragam OSIS SMA',
                'jenis_seragam' => 'SMA/SMK',
                'harga' => 85000,
                'stok' => 120,
                'deskripsi' => 'Bahan katun premium berlogo OSIS resmi.'
            ],
            [
                'nama_produk' => 'Celana Seragam Abu-Abu SMA',
                'jenis_seragam' => 'SMA/SMK',
                'harga' => 95000,
                'stok' => 80,
                'deskripsi' => 'Bahan drill tebal tahan lama.'
            ],
            [
                'nama_produk' => 'Baju Seragam Pramuka',
                'jenis_seragam' => 'Umum',
                'harga' => 90000,
                'stok' => 5, // Status Low Stock
                'deskripsi' => 'Bahan pramuka standar nasional.'
            ],
            [
                'nama_produk' => 'Jas Almamater Sekolah',
                'jenis_seragam' => 'Umum',
                'harga' => 150000,
                'stok' => 0, // Status Empty
                'deskripsi' => 'Jas almamater puring penuh.'
            ]
        ];

        foreach ($produk as $pr) {
            Produk::updateOrCreate(['nama_produk' => $pr['nama_produk']], $pr);
        }

        // 4. Jalankan Seeder Pesanan Historis (36 bulan data musiman)
        $this->call(PesananHistorisSeeder::class);

        // 5. Jalankan Seeder Supplier
        $this->call(SupplierSeeder::class);
    }
}
