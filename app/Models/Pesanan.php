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
        'tanggal_pesanan',
        'status',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
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

    /* ── Generate No Pesanan ── */
    public static function generateNoPesanan(): string
    {
        $tahun = now()->year;
        $lastNo = static::whereYear('created_at', $tahun)->count() + 1;
        return 'PSN-' . $tahun . '-' . str_pad($lastNo, 3, '0', STR_PAD_LEFT);
    }
}
