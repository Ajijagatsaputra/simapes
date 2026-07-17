<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'ukuran',
        'harga_satuan',
        'total_item',
        'jumlah_terbayar',
        'subtotal',
        'catatan',
        'path_gambar',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_item' => 'integer',
        'jumlah_terbayar' => 'integer',
    ];

    /* ── Computed: jumlah belum terbayar ── */
    public function getJumlahBelumBayarAttribute(): int
    {
        return max(0, $this->total_item - $this->jumlah_terbayar);
    }

    /* ── Computed: status item ── */
    public function getStatusItemAttribute(): string
    {
        if ($this->jumlah_terbayar >= $this->total_item) {
            return 'lunas';
        } elseif ($this->jumlah_terbayar > 0) {
            return 'sebagian';
        }
        return 'belum_bayar';
    }

    /* ── Relasi ── */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pembayaranDetails()
    {
        return $this->hasMany(PembayaranDetail::class, 'detail_pesanan_id');
    }
}
