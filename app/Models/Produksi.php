<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model {

  protected $table = "produksi";  
  protected $fillable = ['pesanan_id','nomor_resi','status_key','progress','catatan','mulai_at','selesai_at'];
  protected $casts = ['mulai_at'=>'datetime','selesai_at'=>'datetime'];

  public function pesanan(){ return $this->belongsTo(Pesanan::class); }
  public function logs(){ return $this->hasMany(ProduksiLog::class)->latest(); }

  public function statusDef(){ return $this->belongsTo(ProduksiStatus::class,'status_key','key'); }
}

