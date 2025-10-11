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
        'pengguna_id',
        'judul',
        'deskripsi',
        'jumlah',
        'status',
        'nomor_resi',
        'disetujui_oleh',
        'tanggal_disetujui',
        'alasan_ditolak'
    ];

    protected $casts = ['tanggal_disetujui' => 'datetime'];

    public function pengguna() {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
