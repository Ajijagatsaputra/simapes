<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'jenis_seragam',
        'harga',
        'deskripsi',
        'stok',
        'tanggal_pembuatan',
        'gambar',
        'spesifikasi_bahan',
        'size_chart',
        'estimasi_bb_tb',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'tanggal_pembuatan' => 'date',
    ];

    /**
     * Dapatkan spesifikasi bahan (custom dari admin atau default).
     */
    public function getSpesifikasiBahanFormattedAttribute(): string
    {
        if (!empty(trim($this->spesifikasi_bahan ?? ''))) {
            return $this->spesifikasi_bahan;
        }

        $jenis = strtolower($this->jenis_seragam);
        if (str_contains($jenis, 'tk')) {
            return "Atasan: Kaos Cotton Combed 30s Premium (Sangat lembut, adem, & aman untuk kulit anak-anak)\nBawahan: Katun Drill Soft (Fleksibel & nyaman untuk beraktivitas)";
        } elseif (str_contains($jenis, 'sd') || str_contains($jenis, 'smp') || str_contains($jenis, 'sma') || str_contains($jenis, 'smk')) {
            return "Atasan: Katun TC Super Deluxe (Adem, halus, menyerap keringat, tidak mudah kusut)\nBawahan: Kain Drill Famatex Original (Tebal, serat rapat, warna tahan lama, tidak mudah pudar)";
        } elseif (str_contains($jenis, 'atribut')) {
            return "Bahan: Kain Drill Premium dengan Bordir Komputer Presisi High-Density & Benang Kualitas Ekspor";
        }

        return "Bahan Katun & Drill Premium standar konveksi seragam sekolah nasional. Adem, nyaman, & jahitan rapi ganda.";
    }

    /**
     * Dapatkan data Size Chart (Custom Teks/JSON atau default).
     */
    public function getSizeChartCustomAttribute(): ?string
    {
        return !empty(trim($this->size_chart ?? '')) ? $this->size_chart : null;
    }

    /**
     * Dapatkan data Size Chart default jika kustom kosong.
     */
    public function getSizeChartDataAttribute(): array
    {
        if (!empty($this->size_chart)) {
            $decoded = json_decode($this->size_chart, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $jenis = strtolower($this->jenis_seragam);
        if (str_contains($jenis, 'tk')) {
            return [
                ['size' => 'S (No. 2-3)', 'baju_ld' => '70 cm', 'baju_pb' => '44 cm', 'bawahan_lp' => '46-60 cm', 'bawahan_pc' => '58 cm'],
                ['size' => 'M (No. 4-5)', 'baju_ld' => '74 cm', 'baju_pb' => '47 cm', 'bawahan_lp' => '50-64 cm', 'bawahan_pc' => '62 cm'],
                ['size' => 'L (No. 6-7)', 'baju_ld' => '78 cm', 'baju_pb' => '50 cm', 'bawahan_lp' => '54-68 cm', 'bawahan_pc' => '66 cm'],
                ['size' => 'XL (No. 8)', 'baju_ld' => '82 cm', 'baju_pb' => '53 cm', 'bawahan_lp' => '58-72 cm', 'bawahan_pc' => '70 cm'],
            ];
        } elseif (str_contains($jenis, 'sd')) {
            return [
                ['size' => 'S (Kelas 1-2)', 'baju_ld' => '78 cm', 'baju_pb' => '52 cm', 'bawahan_lp' => '52-66 cm', 'bawahan_pc' => '68 cm'],
                ['size' => 'M (Kelas 2-3)', 'baju_ld' => '82 cm', 'baju_pb' => '55 cm', 'bawahan_lp' => '56-70 cm', 'bawahan_pc' => '72 cm'],
                ['size' => 'L (Kelas 4-5)', 'baju_ld' => '86 cm', 'baju_pb' => '58 cm', 'bawahan_lp' => '60-74 cm', 'bawahan_pc' => '78 cm'],
                ['size' => 'XL (Kelas 5-6)', 'baju_ld' => '90 cm', 'baju_pb' => '62 cm', 'bawahan_lp' => '64-78 cm', 'bawahan_pc' => '84 cm'],
                ['size' => 'XXL (Jumbo)', 'baju_ld' => '96 cm', 'baju_pb' => '66 cm', 'bawahan_lp' => '68-84 cm', 'bawahan_pc' => '88 cm'],
            ];
        }

        return [
            ['size' => 'S', 'baju_ld' => '88 cm', 'baju_pb' => '65 cm', 'bawahan_lp' => '72-80 cm', 'bawahan_pc' => '92 cm'],
            ['size' => 'M', 'baju_ld' => '94 cm', 'baju_pb' => '68 cm', 'bawahan_lp' => '76-84 cm', 'bawahan_pc' => '95 cm'],
            ['size' => 'L', 'baju_ld' => '100 cm', 'baju_pb' => '71 cm', 'bawahan_lp' => '80-88 cm', 'bawahan_pc' => '98 cm'],
            ['size' => 'XL', 'baju_ld' => '106 cm', 'baju_pb' => '74 cm', 'bawahan_lp' => '84-92 cm', 'bawahan_pc' => '101 cm'],
            ['size' => 'XXL', 'baju_ld' => '112 cm', 'baju_pb' => '77 cm', 'bawahan_lp' => '88-98 cm', 'bawahan_pc' => '104 cm'],
        ];
    }

    /**
     * Dapatkan Estimasi BB/TB Custom jika diisi text oleh admin.
     */
    public function getEstimasiBbTbCustomAttribute(): ?string
    {
        return !empty(trim($this->estimasi_bb_tb ?? '')) ? $this->estimasi_bb_tb : null;
    }

    /**
     * Dapatkan data Estimasi BB & TB default jika kustom kosong.
     */
    public function getEstimasiBbTbDataAttribute(): array
    {
        if (!empty($this->estimasi_bb_tb)) {
            $decoded = json_decode($this->estimasi_bb_tb, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $jenis = strtolower($this->jenis_seragam);
        if (str_contains($jenis, 'tk')) {
            return [
                ['size' => 'S (No. 2-3)', 'bb' => '12 - 16 kg', 'tb' => '90 - 100 cm'],
                ['size' => 'M (No. 4-5)', 'bb' => '16 - 20 kg', 'tb' => '100 - 110 cm'],
                ['size' => 'L (No. 6-7)', 'bb' => '20 - 24 kg', 'tb' => '110 - 120 cm'],
                ['size' => 'XL (No. 8)', 'bb' => '24 - 28 kg', 'tb' => '120 - 128 cm'],
            ];
        } elseif (str_contains($jenis, 'sd')) {
            return [
                ['size' => 'S (Kelas 1-2)', 'bb' => '18 - 25 kg', 'tb' => '115 - 125 cm'],
                ['size' => 'M (Kelas 2-3)', 'bb' => '25 - 32 kg', 'tb' => '125 - 135 cm'],
                ['size' => 'L (Kelas 4-5)', 'bb' => '32 - 40 kg', 'tb' => '135 - 145 cm'],
                ['size' => 'XL (Kelas 5-6)', 'bb' => '40 - 48 kg', 'tb' => '145 - 155 cm'],
                ['size' => 'XXL (Jumbo)', 'bb' => '48 - 58 kg', 'tb' => '150 - 160 cm'],
            ];
        }

        return [
            ['size' => 'S', 'bb' => '35 - 45 kg', 'tb' => '145 - 155 cm'],
            ['size' => 'M', 'bb' => '45 - 55 kg', 'tb' => '155 - 165 cm'],
            ['size' => 'L', 'bb' => '55 - 65 kg', 'tb' => '165 - 172 cm'],
            ['size' => 'XL', 'bb' => '65 - 75 kg', 'tb' => '170 - 178 cm'],
            ['size' => 'XXL', 'bb' => '75 - 85+ kg', 'tb' => '175 - 185 cm'],
        ];
    }
}
