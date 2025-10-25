<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produksi;
use App\Models\ProduksiStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;

class PesananAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin'); 
    }


    public function index(Request $request)
    {
        // status: null => semua
        $allowed = ['menunggu','disetujui','ditolak'];
        $status = $request->filled('status') ? $request->status : null;

        $q = trim((string) $request->q);

        $pesanan = Pesanan::with('pengguna')
            ->when($status, fn($x) => $x->where('status', $status))  // hanya filter jika ada
            ->when($q, function ($x) use ($q) {
                $x->where(function($y) use ($q){
                    $y->where('produk','like',"%$q%")
                    ->orWhere('nomor_resi','like',"%$q%")
                    ->orWhere('bahan','like',"%$q%")
                    ->orWhere('warna','like',"%$q%")
                    ->orWhereHas('pengguna', fn($r)=>$r->where('name','like',"%$q%"));
                });
            })
            ->latest()->paginate(15)->withQueryString();

        return view('admin.pesanan.index', compact('pesanan','status','q'));
    }


    // CREATE
    public function create()
    {
        $customers = User::where('role','customer')->orderBy('name')->get(['id','name','email']);
        return view('admin.pesanan.create', compact('customers'));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validatedData($request, requireCustomer: true);

        // hitung total dari ukuran
        $ukuran = collect($request->input('ukuran_kaos', []))
            ->only(['S','M','L','XL','XXL'])->map(fn($v)=>(int)$v)->filter(fn($v)=>$v>0)->toArray();
        $total = array_sum($ukuran);
        if ($total === 0) $total = (int)$request->input('jumlah', 0);

        $pesanan = Pesanan::create([
            'pengguna_id'   => $request->pengguna_id,
            'produk'        => $data['produk'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'bahan'         => $data['bahan'] ?? null,
            'warna'         => $data['warna'] ?? null,
            'tautan_drive'  => $data['tautan_drive'] ?? null,
            'ukuran_kaos'   => $ukuran ?: null,
            'jumlah'        => max($total, 1),
            'status'        => 'menunggu',
        ]);

        return redirect()->route('admin.pesanan.show', $pesanan)->with('berhasil','Pesanan dibuat.');
    }

    // SHOW
    public function show(Pesanan $pesanan)
    {
        $pesanan->load('pengguna','admin');
        return view('admin.pesanan.show', compact('pesanan'));
    }

    // EDIT
    public function edit(Pesanan $pesanan)
    {
        $customers = User::where('role','customer')->orderBy('name')->get(['id','name','email']);
        return view('admin.pesanan.edit', compact('pesanan','customers'));
    }

    // UPDATE
    public function update(Request $request, Pesanan $pesanan)
    {
        $data = $this->validatedData($request, requireCustomer: false);

        $ukuran = collect($request->input('ukuran_kaos', []))
            ->only(['S','M','L','XL','XXL'])->map(fn($v)=>(int)$v)->filter(fn($v)=>$v>0)->toArray();
        $total = array_sum($ukuran);
        if ($total === 0) $total = (int)$request->input('jumlah', $pesanan->jumlah);

        $pesanan->update([
            'pengguna_id'   => $request->pengguna_id ?: $pesanan->pengguna_id,
            'produk'        => $data['produk'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'bahan'         => $data['bahan'] ?? null,
            'warna'         => $data['warna'] ?? null,
            'tautan_drive'  => $data['tautan_drive'] ?? null,
            'ukuran_kaos'   => $ukuran ?: null,
            'jumlah'        => max($total, 1),
        ]);

        return redirect()->route('admin.pesanan.show', $pesanan)->with('berhasil','Pesanan diperbarui.');
    }


    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();
        return redirect()->route('admin.pesanan.index')->with('berhasil','Pesanan dihapus.');
    }


    public function setujui(Request $request, Pesanan $pesanan)
{
    if ($pesanan->status !== 'menunggu') {
        return back()->with([
            'peringatan' => 'Pesanan sudah diproses.'
        ]);
    }

    if (blank($pesanan->nomor_resi)) {
        $pesanan->nomor_resi = $this->buatNomorResi(); // sesuai fungsi kamu
    }

    $pesanan->update([
        'status'            => 'disetujui',
        'disetujui_oleh'    => Auth::id(),
        'tanggal_disetujui' => now(),
        'nomor_resi'        => $pesanan->nomor_resi,
    ]);

    $produksi = Produksi::firstOrCreate(
        ['pesanan_id' => $pesanan->id],
        [
            'nomor_resi' => $pesanan->nomor_resi,
            'status_key' => ProduksiStatus::orderBy('urutan')->value('key') ?? 'desain',
            'mulai_at'   => now(),
        ]
    );

    $produksi->logs()->create([
        'status_key'   => ProduksiStatus::orderBy('urutan')->value('key') ?? 'desain',
        'catatan'      => 'Produksi dibuat dari persetujuan pesanan',
        'created_by'   => Auth::id(),
    ]);


    $pelanggan = $pesanan->pengguna ?? null; 


    if ($pelanggan) {

        $rawPhone = $pelanggan->no_hp ?? null;

        $targetPhone = null;
        if ($rawPhone) {
            $clean = preg_replace('/[^0-9]/', '', $rawPhone);

            if (Str::startsWith($clean, '0')) {

                $targetPhone = '62' . substr($clean, 1);
            } elseif (Str::startsWith($clean, '62')) {
                $targetPhone = $clean;
            } else {
                $targetPhone = $clean;
            }
        }

        // Siapkan pesan
         $namaPelanggan = $pelanggan->name ?? '-';
            $nomorResi     = $pesanan->nomor_resi ?? '-';
            $namaProduk    = $pesanan->produk ?? '-';
            $jumlahTotal   = $pesanan->jumlah ?? '-';
            $catatan   = $pesanan->deskripsi ?? '-';

            $pesanWa  = "Halo {$namaPelanggan}, pesanan kamu sudah DISETUJUI 🎉\n\n";
            $pesanWa .= "Nomor Resi: {$nomorResi}\n";
            $pesanWa .= "Produk: {$namaProduk}\n";
            $pesanWa .= "Jumlah: {$jumlahTotal} pcs\n";
            $pesanWa .= "Catatan: {$catatan} pcs\n\n";
            $pesanWa .= "Kami akan mulai proses produksi. Kamu bisa cek progres di halaman tracking kami, atau hubungi kami jika ada perubahan.\n\n";
            $pesanWa .= "Terima kasih 🙌";

            if ($targetPhone) {
                try {
                    $ok = FonnteService::sendMessage($targetPhone, $pesanWa);

                    if (!$ok) {
                        Log::warning('FonnteService::sendMessage retur false', [
                            'phone' => $targetPhone,
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error('Gagal kirim WA via Fonnte', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            } else {
                Log::warning('No phone to send WA', [
                    'user_id' => $pelanggan->id ?? null,
                    'no_hp_raw' => $pelanggan->no_hp ?? null,
                ]);
            }


            return back()->with('berhasil', 'Pesanan disetujui & notifikasi dikirim.');
        }
    }


    public function tolak(Request $request, Pesanan $pesanan)
    {

        $validated = $request->validate([
            'alasan' => ['required','string','max:2000'],
        ]);

        if ($pesanan->status !== 'menunggu') {
            return redirect()
                ->route('admin.pesanan.show', $pesanan)
                ->with('peringatan', 'Pesanan sudah diproses sebelumnya.');
        }

        // 3. Update pesanan di database
        $pesanan->update([
            'status'          => 'ditolak',
            'alasan_ditolak'  => $validated['alasan'],
        ]);


        try {

            $targetPhone = optional($pesanan->pengguna)->no_hp;

            if ($targetPhone) {


                $clean = preg_replace('/\D+/', '', $targetPhone); // hapus non-digit
                if (str_starts_with($clean, '0')) {
                    $clean = '62'.substr($clean, 1);
                }

                $waPhone = $clean ?: $targetPhone;


                $pesanWa =
                    "Halo {$pesanan->pengguna->name}, maaf pesanan kamu *DITOLAK* ❌\n\n".
                    "Produk: {$pesanan->produk}\n".
                    "Jumlah: {$pesanan->jumlah_total} pcs\n".
                    "Alasan penolakan:\n".
                    "{$validated['alasan']}\n\n".
                    "Silakan hubungi kami untuk perbaikan pesanan 🙏";


                $ok = FonnteService::sendMessage($waPhone, $pesanWa);


                if (! $ok) {
                    Log::warning('Gagal kirim WA penolakan via Fonnte', [
                        'pesanan_id' => $pesanan->id,
                        'phone'      => $waPhone,
                    ]);
                }
            } else {
                // ga ada no hp
                Log::info('WA penolakan tidak terkirim: customer tidak punya no_hp', [
                    'pesanan_id' => $pesanan->id,
                ]);
            }

        } catch (\Throwable $e) {
            // Jangan sampai error WA nge-block flow utama
            Log::error('Error kirim WA penolakan', [
                'pesanan_id' => $pesanan->id,
                'error'      => $e->getMessage(),
            ]);
        }

        // 5. Balik ke halaman detail dengan flash message sukses
        return redirect()
            ->route('admin.pesanan.show', $pesanan)
            ->with('berhasil', 'Pesanan ditolak & notifikasi dikirim.');
    }

    protected function buatNomorResi(): string
    {
        do {
            $resi = 'TSBLN-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        } while (Pesanan::where('nomor_resi',$resi)->exists());
        return $resi;
    }

    // VALIDASI REUSABLE
    protected function validatedData(Request $request, bool $requireCustomer): array
    {
        $rules = [
            'produk'        => ['required','string','max:150'],
            'deskripsi'     => ['nullable','string','max:5000'],
            'bahan'         => ['nullable','string','max:100'],
            'warna'         => ['nullable','string','max:100'],
            'tautan_drive'  => ['nullable','url','max:255'],
            'ukuran_kaos'   => ['array'],
            'ukuran_kaos.*' => ['nullable','integer','min:0'],
            'jumlah'        => ['nullable','integer','min:1'],
        ];
        if ($requireCustomer) $rules['pengguna_id'] = ['required','exists:users,id'];

        return $request->validate($rules);
    }
}
