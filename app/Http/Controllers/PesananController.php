<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->q);
        $pesanan = Pesanan::with('pengguna')
            ->when($request->status, fn($x)=>$x->where('status',$request->status))
            ->when($q, function ($x) use ($q) {
                $x->where('judul','like',"%$q%")
                ->orWhere('nomor_resi','like',"%$q%")
                ->orWhereHas('pengguna', fn($r)=>$r->where('name','like',"%$q%"));
            })
            ->latest()->paginate(10);

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function buat()
    {
        return view('pesanan.buat');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:5000',
            'jumlah' => 'required|integer|min:1',
        ]);

        auth()->user()->pesanan()->create($request->only(['judul','deskripsi','jumlah']));

        return redirect()->route('pesanan.index')->with('berhasil','Pesanan berhasil dibuat dan menunggu persetujuan admin.');
    }

    public function tampil(Pesanan $pesanan)
    {
        if ($pesanan->pengguna_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('pesanan.tampil', compact('pesanan'));
    }
}

