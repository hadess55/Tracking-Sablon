<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'nomor' => ['nullable', 'string', 'max:100']
        ]);

        $nomor = trim((string) $request->query('nomor', ''));

        $produksi = null;
        $statusNow = null;

        if ($nomor !== '') {
            $produksi = Produksi::with([
                'logs' => function ($query) {
                    $query->orderByDesc('created_at')
                        ->orderByDesc('id');
                },
                'pesanan.pengguna:id,name,email',
            ])
                ->where('nomor_resi', $nomor)
                ->orWhereHas('pesanan', function ($q) use ($nomor) {
                    $q->where('nomor_resi', $nomor);
                })
                ->first();

            if ($produksi) {
                $latest = $produksi->logs->first();

                $rawStatus = $latest?->tahapan
                    ?? $latest?->status_key
                    ?? $latest?->status
                    ?? $latest?->label;

                $statusNow = $rawStatus
                    ?: Arr::get($produksi, 'status_sekarang')
                    ?: Arr::get($produksi, 'status', 'Antri');
            }
        }

        $steps = [
            'Antri',
            'Desain',
            'Sablon',
            'Finishing',
            'Packaging',
            'Selesai',
        ];

        return view('public.home', [
            'nomor'     => $nomor,
            'produksi'  => $produksi,
            'statusNow' => $statusNow,
            'steps'     => $steps,
        ]);
    }

    public function show(string $resi)
    {
        return redirect()->route('tracking.index', [
            'nomor' => $resi
        ]);
    }
}
