<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_pesanan',
        'user_id',
        'total_harga',
        'total_terbayar',
        'sisa_tagihan',
        'status_pembayaran',
        'tanggal_pesanan',
        'status',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'total_terbayar' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
        'tanggal_pesanan' => 'date',
    ];

    /* ── Relasi ── */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    /* ── Helper: Hitung ulang total pembayaran ── */
    public function recalculatePembayaran(): void
    {
        $totalTerbayar = $this->pembayarans()
            ->where('status', 'verified')
            ->sum('jumlah_bayar');

        $sisaTagihan = max(0, $this->total_harga - $totalTerbayar);

        if ($totalTerbayar <= 0) {
            $statusPembayaran = 'belum_bayar';
        } elseif ($sisaTagihan <= 0) {
            $statusPembayaran = 'lunas';
        } else {
            $statusPembayaran = 'dp';
        }

        $this->update([
            'total_terbayar' => $totalTerbayar,
            'sisa_tagihan' => $sisaTagihan,
            'status_pembayaran' => $statusPembayaran,
        ]);
    }

    /* ── Helper: Hitung ulang jumlah_terbayar per item ── */
    public function recalculateItemCoverage(): void
    {
        foreach ($this->details as $detail) {
            $totalCover = PembayaranDetail::whereHas('pembayaran', function ($q) {
                $q->where('pesanan_id', $this->id)->where('status', 'verified');
            })->where('detail_pesanan_id', $detail->id)->sum('jumlah_cover');

            $detail->update(['jumlah_terbayar' => min($totalCover, $detail->total_item)]);
        }
    }

    /* ── Generate No Pesanan ── */
    public static function generateNoPesanan(): string
    {
        $tahun = now()->year;

        // Cari pesanan terakhir di tahun ini yang memiliki format 'PSN-YYYY-XXX'
        $lastOrder = static::whereYear('created_at', $tahun)
            ->where('no_pesanan', 'LIKE', "PSN-{$tahun}-%")
            ->orderBy('no_pesanan', 'desc')
            ->first();

        $lastNo = 0;
        if ($lastOrder) {
            // Ambil bagian nomor urut di paling akhir (misal dari 'PSN-2026-122' diambil 122)
            $parts = explode('-', $lastOrder->no_pesanan);
            $lastNo = (int) end($parts);
        }

        $nextNo = $lastNo + 1;
        return 'PSN-' . $tahun . '-' . str_pad($nextNo, 3, '0', STR_PAD_LEFT);
    }
}
