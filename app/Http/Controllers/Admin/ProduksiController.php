<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProduksiRequest;
use App\Http\Requests\UpdateProduksiRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Customers;
use App\Models\Produksi;
use App\Models\RiwayatProduksi;
use Illuminate\Support\Facades\DB;


class ProduksiController extends Controller
{

    private array $statuses = [
        'antri'     => 'Antri',
        'desain'    => 'Desain',
        'cetak'     => 'Cetak',
        'finishing' => 'Finishing',
        'selesai'   => 'Selesai',
    ];


    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));


        $filter = $request->query('filter', 'semua');
        if (! in_array($filter, ['semua','proses','selesai'])) {
            $filter = 'semua';
        }

        $produksis = Produksi::with('pelanggan')
            ->when($q, function ($builder) use ($q) {
                $builder->where('nomor_produksi', 'like', "%{$q}%")
                    ->orWhereHas('pelanggan', function ($rel) use ($q) {
                        $rel->where('nama_lengkap', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('nomor_hp', 'like', "%{$q}%");
                    });
            })
            ->when($filter === 'proses', function ($builder) {
                $builder->where('status_sekarang', '!=', 'Selesai');
            })
            ->when($filter === 'selesai', function ($builder) {
                $builder->where('status_sekarang', 'Selesai');
            })
            ->latest()
            ->paginate(10)
            ->appends(compact('q','filter'));

        $totalProduksi = Produksi::count();
        $sedangProses  = Produksi::where('status_sekarang', '!=', 'Selesai')->count();
        $selesai       = Produksi::where('status_sekarang', 'Selesai')->count();

        return view('admin.produksi.index', compact(
            'produksis', 'q', 'filter', 'totalProduksi', 'sedangProses', 'selesai'
        ));
    }


    public function quick(Request $request)
    {
        $term = trim($request->get('q', ''));
        if (strlen($term) < 2) {
            return response()->json(['items' => []]);
        }

        $items = Produksi::with('pelanggan:id,nama_lengkap')
            ->where('nomor_produksi', 'like', "%{$term}%")
            ->orWhereHas('pelanggan', function ($q) use ($term) {
                $q->where('nama_lengkap', 'like', "%{$term}%");
            })
            ->limit(8)
            ->get(['id', 'nomor_produksi', 'customer_id']); // sesuaikan FK jika namanya berbeda

        return response()->json([
            'items' => $items->map(fn ($p) => [
                'id'   => $p->id,
                'text' => $p->nomor_produksi.' – '.optional($p->pelanggan)->nama_lengkap,
                'url'  => route('admin.produksi.show', $p),   // sesuaikan bila rute detail beda
            ]),
        ]);
    }


    public function create()
    {
        $customers = Customers::where('status_persetujuan', 'disetujui')
                      ->orderBy('nama_lengkap')->get(['id','nama_lengkap']);
        $statuses  = $this->statuses;

        $produksi = new Produksi(['status_sekarang' => 'antri', 'jumlah' => 1]);

        return view('admin.produksi.create', compact('customers','statuses','produksi'));
    }

   public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required','exists:customers,id'],
            'produk'      => ['required','string','max:150'],
            'jumlah'      => ['required','integer','min:1'],
            'catatan'     => ['nullable','string','max:500'],
        ]);

        $data['nomor_produksi'] = Produksi::buatNomorProduksi();

        Produksi::create($data);

        return redirect()
            ->route('admin.produksi.index')
            ->with('sukses', 'Produksi berhasil dibuat.');
    }

        public function show(Produksi $produksi)
        {
            $produksi->load(['pelanggan','riwayat' => fn($q)=>$q->orderByDesc('dilakukan_pada')]);
            $statuses = $this->statuses;

            return view('admin.produksi.show', compact('produksi','statuses'));
        }

        public function edit(Produksi $produksi)
        {
            $customers = Customers::where('status_persetujuan', 'disetujui')
                        ->orderBy('nama_lengkap')->get(['id','nama_lengkap']);
            $statuses  = $this->statuses;

            return view('admin.produksi.edit', compact('produksi','customers','statuses'));
        }

    public function update(UpdateProduksiRequest $request, Produksi $produksi)
    {
        $data = $request->validated();
        $statusLama = $produksi->status_sekarang;

        DB::transaction(function () use ($produksi, $data, $statusLama) {
            $produksi->update($data);

            // bila status berubah, buat entri riwayat baru
            if ($statusLama !== $data['status_sekarang']) {
                RiwayatProduksi::create([
                    'produksi_id'   => $produksi->id,
                    'tahapan'        => $data['status_sekarang'],
                    'catatan'       => $data['catatan'] ?? null,
                    'dilakukan_pada'=> now(),
                ]);
            }
        });

        return redirect()->route('admin.produksi.show', $produksi)
                         ->with('sukses', 'Produksi diperbarui.');
    }

    /** Tambah langkah/timeline dari halaman Show */
    public function tambahRiwayat(Produksi $produksi, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tahapan' => ['required','string','max:50'],
            'catatan' => ['nullable','string','max:500'],
        ]);

        DB::transaction(function () use ($produksi, $data) {
            // simpan riwayat baru
            RiwayatProduksi::create([
                'produksi_id'   => $produksi->id,
                'tahapan'       => $data['tahapan'],
                'catatan'       => $data['catatan'] ?? null,
                'dilakukan_pada'=> now(),
            ]);

            // update status terkini & catatan produksi
            $produksi->update([
                'status_sekarang' => $data['tahapan'],
                'catatan'         => $data['catatan'] ?? $produksi->catatan,
            ]);
        });

        return back()->with('sukses', 'Riwayat ditambahkan.');
    }
}
