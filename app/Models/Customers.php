<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customers extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'nomor_hp',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'status_persetujuan',
        'disetujui_oleh',
        'disetujui_pada',
    ];

     protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'disetujui_pada' => 'datetime',
    ];
    public function produksi() { return $this->hasMany(Produksi::class, 'customer_id'); }

    public function scopeMenunggu($q)
    {
        return $q->where('status_persetujuan', 'menunggu');
    }

}
