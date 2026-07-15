<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresProduksi extends Model
{
    use HasFactory;

    protected $table = 'progres_produksis';

    protected $fillable = [
        'pesanan_id',
        'tahapan',
        'jumlah_pcs',
        'dokumentasi',
        'catatan',
    ];

    protected $casts = [
        'jumlah_pcs' => 'integer',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
