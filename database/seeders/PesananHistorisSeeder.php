<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PesananHistorisSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada pelanggan dan produk
        $pelanggan = User::where('email', '!=', 'admin@gmail.com')->get();
        if ($pelanggan->isEmpty()) {
            $this->call(DatabaseSeeder::class);
            $pelanggan = User::where('email', '!=', 'admin@gmail.com')->get();
        }

        $produkList = Produk::all();
        if ($produkList->isEmpty()) {
            return;
        }

        // Jangan timpa atau jalankan seeder jika sudah ada data pesanan real di database
        if (Pesanan::count() > 0) {
            return;
        }

        $mulaiBulan = Carbon::now()->subMonths(36);
        $tahunMulai = $mulaiBulan->year;

        // Pola musiman: jumlah pesanan (bukan item) per bulan pada tahun dasar
        $polaPesanan = [
            1  => 4,  // Jan (sepi)
            2  => 5,  // Feb
            3  => 6,  // Mar
            4  => 8,  // Apr
            5  => 12, // Mei (mulai naik)
            6  => 25, // Jun (puncak pendaftaran sekolah)
            7  => 32, // Jul (puncak ajaran baru)
            8  => 20, // Ags (pesanan susulan)
            9  => 10, // Sep
            10 => 7,  // Okt
            11 => 5,  // Nov
            12 => 3,  // Des (sangat sepi)
        ];

        $orderCounter = 1;

        // Loop untuk 36 bulan ke belakang
        for ($i = 1; $i <= 36; $i++) {
            $currentDate = $mulaiBulan->copy()->addMonths($i);
            $bulanAngka = $currentDate->month;

            // Faktor pertumbuhan tahunan (trend) sebesar ~10% per tahun
            $selisihTahun = $currentDate->year - $tahunMulai;
            $trendFactor = 1.0 + ($selisihTahun * 0.10);

            // Tentukan jumlah pesanan bulan ini (dengan sedikit noise acak)
            $noise = rand(-2, 2);
            $jumlahPesananBulanIni = max(2, round(($polaPesanan[$bulanAngka] * $trendFactor) + $noise));

            for ($j = 0; $j < $jumlahPesananBulanIni; $j++) {
                // Pilih hari acak pada bulan tersebut
                $hariAcak = rand(1, $currentDate->daysInMonth);
                $tanggalPesanan = Carbon::create($currentDate->year, $currentDate->month, $hariAcak);

                // Buat no_pesanan format unik
                $noPesanan = 'PSN-' . $tanggalPesanan->format('Ymd') . '-' . str_pad($orderCounter++, 4, '0', STR_PAD_LEFT);

                // Pilih pelanggan acak
                $cust = $pelanggan->random();

                // Tentukan status pesanan
                // Jika pesanan di bulan berjalan (sekarang), status bisa bervariasi. Jika sudah lama, status 'selesai'.
                if ($tanggalPesanan->isCurrentMonth()) {
                    $status = ['diproses', 'dikerjakan', 'selesai'][rand(0, 2)];
                } else {
                    $status = 'selesai';
                }

                // Insert data pesanan
                $pesanan = Pesanan::create([
                    'no_pesanan' => $noPesanan,
                    'user_id' => $cust->id,
                    'total_harga' => 0, // dihitung setelah detail pesanan dimasukkan
                    'tanggal_pesanan' => $tanggalPesanan->format('Y-m-d'),
                    'status' => $status,
                    'created_at' => $tanggalPesanan,
                    'updated_at' => $tanggalPesanan,
                ]);

                // Buat detail pesanan (1-3 produk per pesanan)
                $jumlahProdukPilihan = rand(1, 3);
                $produkTerpilih = $produkList->random($jumlahProdukPilihan);
                $totalHargaPesanan = 0;

                foreach ($produkTerpilih as $prod) {
                    $qty = rand(10, 50); // Konveksi biasanya pesan lusinan/kodian
                    $ukuran = ['S', 'M', 'L', 'XL'][rand(0, 3)];
                    $subtotal = $prod->harga * $qty;
                    $totalHargaPesanan += $subtotal;

                    DB::table('detail_pesanans')->insert([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $prod->id,
                        'ukuran' => $ukuran,
                        'harga_satuan' => $prod->harga,
                        'total_item' => $qty,
                        'subtotal' => $subtotal,
                        'created_at' => $tanggalPesanan,
                        'updated_at' => $tanggalPesanan,
                    ]);
                }

                // Update total_harga di tabel pesanan
                $pesanan->update([
                    'total_harga' => $totalHargaPesanan
                ]);
            }
        }
    }
}
