<?php

// app/Http/Controllers/TrackingController.php
namespace App\Http\Controllers;
use App\Models\Produksi;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
  public function show(string $resi){
    $prod = Produksi::with(['pesanan.pengguna','statusDef','logs'])
      ->where('nomor_resi',$resi)->firstOrFail();

    return view('public.tracking', compact('prod'));
  }

  public function api(Request $r){
    $resi = $r->query('resi');
    $prod = Produksi::with(['statusDef','logs'])->where('nomor_resi',$resi)->first();
    if (!$prod) return response()->json(['error'=>'Resi tidak ditemukan'], 404);

    return [
      'resi'     => $prod->nomor_resi,
      'status'   => $prod->statusDef?->label ?? $prod->status_key,
      'progress' => $prod->progress,
      'logs'     => $prod->logs->map(fn($l)=>[
        'waktu'  => $l->created_at->toDateTimeString(),
        'status' => $l->status_key,
        'catatan'=> $l->catatan,
      ]),
    ];
  }
}
