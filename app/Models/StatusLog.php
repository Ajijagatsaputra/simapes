<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
    public $timestamps = false;

    protected $table = 'status_logs';

    protected $fillable = [
        'pesanan_id',
        'status',
        'label',
        'catatan',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /** Label map status → bahasa Indonesia */
    const LABELS = [
        'pending' => 'Menunggu Persetujuan',
        'diproses' => 'Disetujui / Diproses',
        'dikerjakan' => 'Sedang Dikerjakan',
        'selesai' => 'Selesai',
        'batal' => 'Dibatalkan / Ditolak',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
