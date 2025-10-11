<?php

// app/Http/Controllers/Admin/PesananAdminController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PesananAdminController extends Controller
{
    public function __construct() { $this->middleware('admin'); }

    public function index(Request $request)
    {
        $status = $request->input('status', 'menunggu');
        $pesanan = Pesanan::where('status', $status)->latest()->paginate(10);
        return view('admin.pesanan.index', compact('pesanan', 'status'));
    }

    public function setujui(Pesanan $pesanan)
    {
        if ($pesanan->status !== 'menunggu') {
            return back()->with('peringatan', 'Pesanan sudah diproses.');
        }

        DB::transaction(function() use ($pesanan) {
            $pesanan->update([
                'status' => 'disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_disetujui' => now(),
                'nomor_resi' => $this->buatNomorResi(),
            ]);
        });

        return back()->with('berhasil', 'Pesanan disetujui. Nomor resi telah dibuat.');
    }

    public function tolak(Request $request, Pesanan $pesanan)
    {
        $request->validate(['alasan' => 'required|string|max:2000']);

        if ($pesanan->status !== 'menunggu') {
            return back()->with('peringatan', 'Pesanan sudah diproses.');
        }

        $pesanan->update([
            'status' => 'ditolak',
            'disetujui_oleh' => auth()->id(),
            'alasan_ditolak' => $request->alasan,
        ]);

        return back()->with('berhasil', 'Pesanan ditolak dengan alasan: '.$request->alasan);
    }

    protected function buatNomorResi(): string
    {
        do {
            $resi = 'RESI-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        } while (Pesanan::where('nomor_resi', $resi)->exists());

        return $resi;
    }
}
