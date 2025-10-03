<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->get('q'));            // kata kunci pencarian
        $status = $request->get('status');                      // menunggu | disetujui | ditolak

        $customers = Customers::query()
            ->when(
                $status && in_array($status, ['menunggu','disetujui','ditolak']),
                fn ($w) => $w->where('status_persetujuan', $status)
            )
            ->when($q, function ($w) use ($q) {
                // cari di beberapa kolom
                $w->where(function ($x) use ($q) {
                    $x->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('email',       'like', "%{$q}%")
                    ->orWhere('nomor_hp',    'like', "%{$q}%")
                    ->orWhere('alamat',      'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'     => Customers::count(),
            'menunggu'  => Customers::where('status_persetujuan', 'menunggu')->count(),
            'disetujui' => Customers::where('status_persetujuan', 'disetujui')->count(),
            'ditolak'   => Customers::where('status_persetujuan', 'ditolak')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'q', 'status', 'stats'));
    }

    public function quick(Request $request)
    {
        $term = trim($request->get('q', ''));
        if (strlen($term) < 2) {
            return response()->json(['items' => []]);
        }

        $rows = Customers::query()
            ->select(['id','nama_lengkap','email','nomor_hp','status_persetujuan'])
            ->where('nama_lengkap', 'like', "%{$term}%")
            ->orWhere('email',       'like', "%{$term}%")
            ->orWhere('nomor_hp',    'like', "%{$term}%")
            ->limit(8)
            ->get();

        return response()->json([
            'items' => $rows->map(fn ($c) => [
                'id'    => $c->id,
                'text'  => $c->nama_lengkap.' – '.$c->email,
                'sub'   => $c->nomor_hp,
                'badge' => strtolower($c->status_persetujuan ?: 'menunggu'),
                'url'   => route('admin.customers.show', $c), 
            ]),
        ]);
    }




    public function setujui(Customers $customer)
    {
        $customer->update([
            'status_persetujuan' => 'disetujui',
            'disetujui_oleh' => Auth::id(),
            'disetujui_pada' => now(),
        ]);

        return back()->with('sukses', 'Pelanggan berhasil disetujui.');
    }


    public function tolak(Customers $customer)
    {
        $customer->update([
            'status_persetujuan' => 'ditolak',
            'disetujui_oleh' => Auth::id(),
            'disetujui_pada' => now(),
        ]);

        return back()->with('sukses', 'Pelanggan ditolak.');
    }
}
