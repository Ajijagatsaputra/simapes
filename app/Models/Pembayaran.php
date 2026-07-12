<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'termin_ke',
        'jumlah_bayar',
        'tanggal_bayar',
        'metode_pembayaran',
        'catatan',
        'bukti_bayar',
        'catatan_pelanggan',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'tanggal_bayar' => 'date',
        'verified_at' => 'datetime',
    ];

    /* ── Relasi ── */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function details()
    {
        return $this->hasMany(PembayaranDetail::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
