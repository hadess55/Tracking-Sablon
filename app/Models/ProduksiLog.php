<?php

// app/Models/ProduksiLog.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProduksiLog extends Model {
   protected $table = "produksi_logs"; 
  protected $fillable = ['produksi_id','status_key','catatan','created_by'];
  public function produksi(){ return $this->belongsTo(Produksi::class); }
  public function author(){ return $this->belongsTo(User::class,'created_by'); }
}
