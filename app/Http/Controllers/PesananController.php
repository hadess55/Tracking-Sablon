<?php


namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        // nilai default "semua"
        $status = strtolower($request->query('status', 'semua'));

        $items = Pesanan::where('pengguna_id', Auth::id())
            ->when($status !== 'semua', function ($q) use ($status) {
                $q->whereRaw('LOWER(status) = ?', [$status]);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); 

        return view('customer.pesanan.index', [
            'items'  => $items,
            'aktif'  => $status,
        ]);
    }
    /** GET /pesanan/buat */
    public function buat()
    {
        return view('customer.pesanan.buat');
    }

    /** POST /pesanan */
    public function simpan(Request $r)
    {
        $data = $r->validate([
            'produk'         => ['required','string','max:100'],
            'bahan'          => ['required','string','max:100'],
            'drive_link'     => ['nullable','url','max:255'],
            'warna'          => ['required','string','max:50'],
            'deskripsi'      => ['nullable','string','max:2000'],
            'ukuran'         => ['nullable','array'],
            'ukuran.s'       => ['nullable','integer','min:0'],
            'ukuran.m'       => ['nullable','integer','min:0'],
            'ukuran.l'       => ['nullable','integer','min:0'],
            'ukuran.xl'      => ['nullable','integer','min:0'],
            'ukuran.xxl'     => ['nullable','integer','min:0'],
            'jumlah_manual'  => ['nullable','integer','min:0'],
        ]);

        $uk = array_map(fn($v)=>(int)($v ?? 0), $data['ukuran'] ?? []);
        $totalBySize = ($uk['s'] ?? 0)+($uk['m'] ?? 0)+($uk['l'] ?? 0)+($uk['xl'] ?? 0)+($uk['xxl'] ?? 0);
        $jumlah = $totalBySize > 0 ? $totalBySize : (int)($data['jumlah_manual'] ?? 0);

        if ($jumlah <= 0) {
            return back()
                ->withErrors(['jumlah_manual' => 'Jumlah harus diisi (melalui per-size atau jumlah manual).'])
                ->withInput();
        }

        $pesanan = Pesanan::create([
            'pengguna_id' => Auth::id(),
            'produk'      => $data['produk'],
            'bahan'       => $data['bahan'],
            'warna'       => $data['warna'],
            'drive_link'  => $data['drive_link'] ?? null,
            'deskripsi'   => $data['deskripsi'] ?? null,
            'ukuran'      => $uk,                  // simpan sebagai JSON (cast di model)
            'jumlah'      => $jumlah,
            'status'      => 'menunggu',          // default: menunggu persetujuan admin
            // 'nomor_resi' => null,               // diisi saat admin setujui
        ]);

        return redirect()
            ->route('pesanan.tampil', $pesanan)
            ->with('berhasil', 'Pesanan terkirim. Menunggu persetujuan admin.');
    }

    /** GET /pesanan (daftar pesanan customer) */


    /** GET /pesanan/{pesanan} (detail pesanan customer) */
    // app/Http/Controllers/PesananController.php
    public function tampil(Pesanan $pesanan)
    {
        $this->authorize('view', $pesanan);
        return view('customer.pesanan.tampil', compact('pesanan'));
    }


}

