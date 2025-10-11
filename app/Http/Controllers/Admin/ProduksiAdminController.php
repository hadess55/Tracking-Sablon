<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Produksi;
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
        // salah satu harus ada
        'status_key'   => ['nullable','exists:produksi_status,key'],
        'status_label' => ['required_without:status_key','string','max:100'],
        'progress'     => ['nullable','integer','min:0','max:100'],
        'catatan'      => ['nullable','string','max:2000'],
    ]);

    // Ambil/ buat status
    if ($r->filled('status_key')) {
        $key = $r->string('status_key');
        $status = ProduksiStatus::where('key',$key)->firstOrFail();
    } else {
        // user mengetik label baru
        $label = trim($r->string('status_label'));
        $key = Str::slug($label); // contoh pembuatan key
        $status = ProduksiStatus::firstOrCreate(
            ['key' => $key],
            [
                'label'    => $label,
                'urutan'   => (int) (ProduksiStatus::max('urutan') + 1),
                'is_final' => false,
            ]
        );
    }

    $produksi->update([
        'status_key' => $status->key,
        'progress'   => $r->filled('progress') ? (int)$r->progress : null,
        'catatan'    => $r->catatan,
        'selesai_at' => ($status->is_final ? now() : null),
    ]);

    $produksi->logs()->create([
        'status_key' => $status->key,
        'catatan'    => $r->catatan,
        'created_by' => Auth::id(), 
    ]);

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
}

