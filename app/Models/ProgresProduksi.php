<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresProduksi extends Model
{
    use HasFactory;

    public $timestamps = false; // Kita pakai selesai_pada manual, bukan timestamps standar
    // Tapi kita tetap perlu created_at untuk menyimpan kapan dibuat
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $table = 'progres_produksis';

    /**
     * 5 Tahapan produksi yang tetap / fixed
     */
    const TAHAPAN = [
        1 => 'Persiapan Bahan',
        2 => 'Pemotongan Bahan',
        3 => 'Penjahitan Baju',
        4 => 'Packing / Finishing',
        5 => 'Selesai',
    ];

    protected $fillable = [
        'pesanan_id',
        'tahapan_ke',
        'tahapan',
        'jumlah_pcs',
        'dokumentasi',
        'catatan',
        'selesai_pada',
    ];

    protected $casts = [
        'jumlah_pcs' => 'integer',
        'tahapan_ke' => 'integer',
        'selesai_pada' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /** Apakah tahapan ini sudah selesai */
    public function isSelesai(): bool
    {
        return !is_null($this->selesai_pada);
    }
}
