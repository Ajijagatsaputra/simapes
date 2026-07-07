<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembayaran_id',
        'detail_pesanan_id',
        'jumlah_cover',
        'nominal_cover',
    ];

    protected $casts = [
        'jumlah_cover' => 'integer',
        'nominal_cover' => 'decimal:2',
    ];

    /* ── Relasi ── */
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function detailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class, 'detail_pesanan_id');
    }
}
