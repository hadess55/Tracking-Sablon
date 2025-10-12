<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
{
    $request->validate(['nomor' => ['nullable','string','max:100']]);

    $nomor     = trim((string) $request->query('nomor', ''));
    $produksi  = null;
    $statusNow = null;

    if ($nomor !== '') {

        $produksi = Produksi::with([
                'logs',                      
                'pesanan.pengguna:id,name,email', 
            ])
            ->where('nomor_resi', $nomor)
            ->orWhereHas('pesanan', fn ($q) => $q->where('nomor_resi', $nomor))
            ->first();

        if ($produksi) {

            $logCollection = $produksi->logs ?? collect();

            if (method_exists($produksi, 'riwayat')) {
                $logCollection = $logCollection->merge($produksi->riwayat);
            }

            $latest = $logCollection
                ->sortByDesc(function ($l) {
                    return $l->created_at
                        ?? $l->dilakukan_pada
                        ?? $l->mulai_at
                        ?? $l->updated_at
                        ?? now()->subYears(50);
                })
                ->first();


            $rawStatus = optional($latest)->tahapan
                    ?? optional($latest)->status_key
                    ?? optional($latest)->status
                    ?? optional($latest)->label;


            $statusNow = $rawStatus
                    ?:\Illuminate\Support\Arr::get($produksi, 'status_sekarang')
                    ?:\Illuminate\Support\Arr::get($produksi, 'status', '-');
        }
    }

    $steps = ['Antri','Desain','Cetak','Finishing','Packaging','Selesai'];

    return view('public.home', [
        'nomor'     => $nomor,
        'produksi'  => $produksi,
        'statusNow' => $statusNow,
        'steps'     => $steps,
    ]);
}


    // Opsional: akses langsung /tracking/{resi}
    public function show(string $resi)
    {
        return redirect()->route('tracking.show', ['resi' => $resi]);
    }
}
