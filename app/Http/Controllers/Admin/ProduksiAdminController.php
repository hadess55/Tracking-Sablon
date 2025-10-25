<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Produksi;
use Illuminate\Support\Facades\Log;
use App\Services\FonnteService;
use App\Models\ProduksiStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduksiAdminController extends Controller
{
  public function index(Request $r)
{
    $filter = $r->input('filter', 'semua');
    $q = trim((string) $r->q);

    $produksis = Produksi::with(['pesanan.pengguna','statusDef'])
        ->when($q, fn($x)=>$x->where('nomor_resi','like',"%$q%")
            ->orWhereHas('pesanan', fn($y)=>$y->where('produk','like',"%$q%")
                ->orWhereHas('pengguna', fn($z)=>$z->where('name','like',"%$q%"))))
        ->when($filter==='proses', fn($x)=>$x->whereNotIn('status_key',['selesai']))
        ->when($filter==='selesai', fn($x)=>$x->where('status_key','selesai'))
        ->latest()->paginate(15)->withQueryString();

    $totalProduksi = Produksi::count();
    $sedangProses  = Produksi::where('status_key','!=','selesai')->count();
    $selesai       = Produksi::where('status_key','selesai')->count();

    return view('admin.produksi.index', compact(
        'produksis','filter','q','totalProduksi','sedangProses','selesai'
    ));
}

  public function show(Produksi $produksi){
    $produksi->load(['pesanan.pengguna','logs.author','statusDef']);
    $statuses = ProduksiStatus::orderBy('urutan')->get();
    return view('admin.produksi.show', compact('produksi','statuses'));
  }

  public function update(Request $r, Produksi $produksi)
{
    $r->validate([

        'status_key'   => ['nullable','exists:produksi_status,key'],
        'status_label' => ['required_without:status_key','string','max:100'],
        'progress'     => ['nullable','integer','min:0','max:100'],
        'catatan'      => ['nullable','string','max:2000'],
    ]);

    if ($r->filled('status_key')) {
        $key    = $r->string('status_key');
        $status = ProduksiStatus::where('key',$key)->firstOrFail();
    } else {
        // admin ketik status manual
        $label = trim($r->string('status_label'));
        $key   = Str::slug($label);

        $status = ProduksiStatus::firstOrCreate(
            ['key' => $key],
            [
                'label'    => $label,
                'urutan'   => (int) (ProduksiStatus::max('urutan') + 1),
                'is_final' => false,
            ]
        );
    }


    $oldStatusKey   = $produksi->status_key;
    $oldProgress    = $produksi->progress;

    $produksi->update([
        'status_key' => $status->key,
        'progress'   => $r->filled('progress') ? (int)$r->progress : null,
        'catatan'    => $r->catatan,
        'selesai_at' => ($status->is_final ? now() : null),
    ]);


    $log = $produksi->logs()->create([
        'status_key' => $status->key,
        'catatan'    => $r->catatan,
        'created_by' => Auth::id(),
    ]);

    $statusBerubah  = $oldStatusKey !== $status->key;
    $progressBerubah = $oldProgress != ($r->filled('progress') ? (int)$r->progress : null);

    if ($statusBerubah || $progressBerubah) {

        $pelanggan = $produksi->pelanggan
            ?? ($produksi->pesanan->pengguna ?? null);

        if ($pelanggan) {
            $namaPelanggan = $pelanggan->name
                ?? $pelanggan->nama_lengkap
                ?? $pelanggan->nama
                ?? '-';

            $rawPhone = $pelanggan->no_hp
                ?? $pelanggan->phone
                ?? $pelanggan->telp
                ?? null;


            $targetPhone = null;
            if ($rawPhone) {
                $clean = preg_replace('/[^0-9]/', '', $rawPhone); // buang spasi/titik/+
                if (Str::startsWith($clean, '0')) {
                    $targetPhone = '62' . substr($clean, 1);
                } elseif (Str::startsWith($clean, '62')) {
                    $targetPhone = $clean;
                } else {
                    $targetPhone = $clean;
                }
            }


            $nomorProduksi = $produksi->nomor_produksi
                ?? $produksi->nomor_resi
                ?? $produksi->id;

            $produkNama = $produksi->produk
                ?? optional($produksi->pesanan)->produk
                ?? '-';

            $jumlah = $produksi->jumlah
                ?? optional($produksi->pesanan)->jumlah
                ?? optional($produksi->pesanan)->jumlah_total
                ?? '-';

            $progressText = $r->filled('progress')
                ? $r->progress . '%'
                : ($produksi->progress ? $produksi->progress . '%' : null);

            $statusLabel = $status->label ?? Str::title(str_replace('-', ' ', $status->key));


            $trackingUrl = url('/tracking?nomor=' . urlencode($nomorProduksi));

            $catatan = $r->catatan ?: null;

            $pesanWa  = "Halo {$namaPelanggan} 👋\n\n";
            $pesanWa .= "Update terbaru untuk pesanan kamu:\n";
            $pesanWa .= "Nomor Produksi : {$nomorProduksi}\n";
            $pesanWa .= "Status         : {$statusLabel}\n";
            if ($progressText) {
                $pesanWa .= "Progress       : {$progressText}\n";
            }
            $pesanWa .= "Produk         : {$produkNama}\n";
            $pesanWa .= "Jumlah         : {$jumlah} pcs\n";

            if ($catatan) {
                $pesanWa .= "\nCatatan dari tim:\n{$catatan}\n";
            }

            $pesanWa .= "\nPantau perkembangan produksi kamu di sini:\n{$trackingUrl}\n\n";
            $pesanWa .= "Terima kasih 🙏";


            if ($targetPhone) {
                try {
                    $ok = FonnteService::sendMessage($targetPhone, $pesanWa);

                    if (!$ok) {
                        Log::warning('Fonnte gagal kirim notifikasi update produksi', [
                            'produksi_id' => $produksi->id,
                            'phone'       => $targetPhone,
                            'status_key'  => $status->key,
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error('Gagal kirim WA update produksi', [
                        'error'        => $e->getMessage(),
                        'produksi_id'  => $produksi->id,
                        'phone'        => $targetPhone,
                        'status_key'   => $status->key,
                    ]);
                }
            } else {
                Log::warning('Nomor HP pelanggan kosong/tidak valid, WA tidak dikirim', [
                    'produksi_id' => $produksi->id,
                    'pelanggan_id'=> $pelanggan->id ?? null,
                ]);
            }
        }
    }


    return back()->with('berhasil','Status produksi diperbarui.');
}
  public function quick(Request $r)
{
    $q = trim((string) $r->q);
    $filter = $r->filter;

    $items = Produksi::with(['pesanan.pengguna'])
        ->when($q, function ($x) use ($q) {
            $x->where('nomor_resi','like',"%$q%")
              ->orWhereHas('pesanan', function($y) use ($q){
                  $y->where('produk','like',"%$q%")
                    ->orWhereHas('pengguna', fn($z)=>$z->where('name','like',"%$q%"));
              });
        })
        ->when($filter==='proses', fn($x)=>$x->where('status_key','!=','selesai'))
        ->when($filter==='selesai', fn($x)=>$x->where('status_key','selesai'))
        ->limit(10)->get()
        ->map(fn($p)=>[
            'id'   => $p->id,
            'text' => "{$p->nomor_resi} — ".optional($p->pesanan)->produk,
            'url'  => route('admin.produksi.show', $p),
        ]);

    return response()->json(['items'=>$items]);
}

public function stats()
{
    // Jika kamu pakai tabel produksi_statuses dengan kolom is_final:
    $sedang  = Produksi::whereHas('statusDef', fn($q) => $q->where('is_final', false))->count();
    $selesai = Produksi::whereHas('statusDef', fn($q) => $q->where('is_final', true))->count();

    // Kalau TIDAK pakai is_final dan hanya mengandalkan key "selesai",
    // pakai ini saja:
    // $sedang  = Produksi::where('status_key','!=','selesai')->count();
    // $selesai = Produksi::where('status_key','selesai')->count();

    return response()->json(['sedang' => $sedang, 'selesai' => $selesai]);
}
}

