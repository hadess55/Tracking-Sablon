<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrackingProduksiController extends Controller
{
    /**
     * Halaman lacak + hasil di halaman yang sama.
     * Ambil nomor dari query string: /lacak?nomor=xxxx
     */
    public function form(Request $request): View
    {
        $nomor = trim((string) $request->query('nomor', '')); // ambil nilai dari ?nomor=
        $produksi = null;

        if ($nomor !== '') {
            $produksi = Produksi::with([
                'pelanggan',
                'riwayat' => fn($q) => $q->orderByDesc('dilakukan_pada'),
            ])->where('nomor_produksi', $nomor)->first();
        }

        return view('public.home', compact('nomor', 'produksi'));
    }

    /**
     * Submit dari form (POST) -> redirect ke GET /lacak?nomor=...
     * supaya URL shareable dan hasil tampil di bawah form.
     */
    public function search(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nomor' => ['required', 'string', 'max:100'],
        ]);

        return redirect()->route('tracking', ['nomor' => $data['nomor']]);
    }
}
