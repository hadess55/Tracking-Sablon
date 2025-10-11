<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduksiStatus extends Model
{
    protected $table = "produksi_status";
    protected $fillable = ['key','label','urutan','is_final'];
    public $timestamps = true;
}
