<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatProduksi extends Model
{
    use HasFactory;
    protected $table = 'riwayat_produksi';

     protected $fillable = [
        'produksi_id',
        'tahapan',
        'catatan',
        'lokasi',
        'dilakukan_pada',
    ];

    protected $casts = [
        'dilakukan_pada' => 'datetime',
    ];

    /** Relasi ke produksi */
    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }
}
