<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksi';

    protected $fillable = [
        'customer_id',
        'nomor_produksi',
        'produk',
        'jumlah',
        'status_sekarang',
        'catatan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    /** Relasi ke riwayat produksi */
    public function riwayat()   { return $this->hasMany(RiwayatProduksi::class, 'produksi_id'); }

    /** Generator nomor produksi unik */
    public static function buatNomorProduksi(): string
    {
        do {
            $nomor = 'SBLN-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -4));
        } while (self::where('nomor_produksi', $nomor)->exists());

        return $nomor;
    }

    public const STATUS_PROSES = ['Antri', 'Desain', 'Cetak', 'Finishing'];

    public function scopeSedang($query)
    {
        return $query->where('status_sekarang', '!=', 'Selesai');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_sekarang', 'Selesai');
    }

}
