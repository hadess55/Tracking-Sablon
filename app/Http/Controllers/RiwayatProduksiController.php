<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use Illuminate\Http\Request;

class RiwayatProduksiController extends Controller
{
    /** Tambah tahapan ke suatu produksi */
    public function store(Request $request, Produksi $produksi)
    {
        $data = $request->validate([
            'tahapan'        => 'required|string|max:100',
            'catatan'     => 'nullable|string',
            'lokasi'         => 'nullable|string|max:255',
            'dilakukan_pada' => 'nullable|date',
        ]);

        $data['dilakukan_pada'] = $data['dilakukan_pada'] ?? now();

        $produksi->riwayat()->create($data);

        $produksi->update(['status_sekarang' => $data['tahapan']]);

        return back()->with('sukses', 'Tahapan produksi ditambahkan.');
    }
}
