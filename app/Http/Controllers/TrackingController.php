<?php

namespace App\Http\Controllers;

use App\Models\Produksi;

class TrackingController extends Controller
{
    public function show(string $nomor)
    {
        $produksi = Produksi::with([
                'pelanggan',
                'riwayat' => fn($q) => $q->orderByDesc('dilakukan_pada'),
            ])
            ->where('nomor_produksi', $nomor)
            ->firstOrFail();

        return view('tracking.show', compact('produksi'));
    }
}
