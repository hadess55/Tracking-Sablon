<?php

// app/Models/Pesanan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $fillable = [
        'pengguna_id','produk','deskripsi','jumlah','status',
        'nomor_resi','disetujui_oleh','tanggal_disetujui','alasan_ditolak',
        'bahan','warna','ukuran_kaos','tautan_drive'
    ];

    protected $casts = [
        'tanggal_disetujui' => 'datetime',
        'ukuran_kaos' => 'array',
    ];

    public function getUkuranRingkasAttribute(): string
    {
        $u = $this->ukuran_kaos ?: [];
        $pairs = [];
        foreach (['S','M','L','XL','XXL'] as $sz) {
            $n = (int)($u[$sz] ?? 0);
            if ($n > 0) $pairs[] = "{$sz}:{$n}";
        }
        return implode(', ', $pairs) ?: '—';
    }
    

    public function pengguna() {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
