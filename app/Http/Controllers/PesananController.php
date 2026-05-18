<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $status = strtolower($request->query('status', 'semua'));

        $items = Pesanan::where('pengguna_id', Auth::id())
            ->when($status !== 'semua', function ($q) use ($status) {
                $q->whereRaw('LOWER(status) = ?', [$status]);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customer.pesanan.index', [
            'items' => $items,
            'aktif' => $status,
        ]);
    }
    public function buat()
    {
        return view('customer.pesanan.buat');
    }

    public function simpan(Request $r)
    {
        $data = $r->validate([
            'produk' => ['required', 'string', 'max:100'],
            'bahan' => ['required', 'string', 'max:100'],
            'drive_link' => ['nullable', 'url', 'max:255'],
            'warna' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'ukuran' => ['nullable', 'array'],
            'ukuran.s' => ['nullable', 'integer', 'min:0'],
            'ukuran.m' => ['nullable', 'integer', 'min:0'],
            'ukuran.l' => ['nullable', 'integer', 'min:0'],
            'ukuran.xl' => ['nullable', 'integer', 'min:0'],
            'ukuran.xxl' => ['nullable', 'integer', 'min:0'],
            'jumlah_manual' => ['nullable', 'integer', 'min:0'],
        ]);

        $uk = array_map(fn($v) => (int) ($v ?? 0), $data['ukuran'] ?? []);
        $totalBySize = ($uk['s'] ?? 0) + ($uk['m'] ?? 0) + ($uk['l'] ?? 0) + ($uk['xl'] ?? 0) + ($uk['xxl'] ?? 0);
        $jumlah = $totalBySize > 0 ? $totalBySize : (int) ($data['jumlah_manual'] ?? 0);

        if ($jumlah <= 0) {
            return back()
                ->withErrors(['jumlah_manual' => 'Jumlah harus diisi (melalui per-size atau jumlah manual).'])
                ->withInput();
        }

        $pesanan = Pesanan::create([
            'pengguna_id' => Auth::id(),
            'produk' => $data['produk'],
            'bahan' => $data['bahan'],
            'warna' => $data['warna'],
            'drive_link' => $data['drive_link'] ?? null,
            'deskripsi' => $data['deskripsi'] ?? null,
            'ukuran' => $uk,
            'jumlah' => $jumlah,
            'status' => 'menunggu',
            // 'nomor_resi' => null,
        ]);

        return redirect()->route('pesanan.tampil', $pesanan)->with('berhasil', 'Pesanan terkirim. Menunggu persetujuan admin.');
    }
    public function tampil(Pesanan $pesanan)
    {
        $user = Auth::user();

        $isOwner = $pesanan->pengguna_id == $user->id;

        if ($user->role !== 'customer' && !$isOwner) {
            abort(404);
        }

        return view('customer.pesanan.tampil', compact('pesanan'));
    }

    public function tracking(Pesanan $pesanan)
    {
        $user = Auth::user();

        $isOwner = $pesanan->pengguna_id == $user->id;

        if ($user->role === 'customer' && ! $isOwner) {
            abort(404);
        }

        if (!$pesanan->nomor_resi) {
            return redirect()
                ->route('pesanan.tampil', $pesanan)
                ->with('gagal', 'Nomor resi belum tersedia.');
        }

        $produksi = Produksi::with([
            'logs' => function ($query) {
                $query->orderByDesc('created_at')
                    ->orderByDesc('id');
            },
            'pesanan.pengguna:id,name,email',
        ])
            ->where('nomor_resi', $pesanan->nomor_resi)
            ->orWhereHas('pesanan', function ($q) use ($pesanan) {
                $q->where('nomor_resi', $pesanan->nomor_resi);
            })
            ->first();

        if (!$produksi) {
            return redirect()
                ->route('pesanan.tampil', $pesanan)
                ->with('gagal', 'Data produksi belum tersedia.');
        }

        $latest = $produksi->logs->first();

        $rawStatus = $latest?->tahapan
            ?? $latest?->status_key
            ?? $latest?->status
            ?? $latest?->label;

        $statusNow = $rawStatus
            ?: $produksi->status_sekarang
            ?: $produksi->status
            ?: 'Antri';

        $steps = [
            'Antri',
            'Desain',
            'Cutting',
            'Sablon',
            'Finishing',
            'Packaging',
            'Selesai',
        ];

        return view('public.home', [
            'nomor'     => $pesanan->nomor_resi,
            'produksi'  => $produksi,
            'statusNow' => $statusNow,
            'steps'     => $steps,
        ]);
    }
}
