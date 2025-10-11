<?php

// app/Http/Controllers/Admin/ProduksiStatusController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProduksiStatus;
use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProduksiStatusController extends Controller
{
    public function index()
    {
        $statuses = ProduksiStatus::orderBy('urutan')->get();
        return view('admin.produksi_status.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.produksi_status.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'key'      => ['required','alpha_dash','lowercase','max:50','unique:produksi_statuses,key'],
            'label'    => ['required','string','max:100'],
            'urutan'   => ['nullable','integer','min:0'],
            'is_final' => ['nullable','boolean'],
        ]);
        $data['is_final'] = (bool)($data['is_final'] ?? false);
        $data['urutan']   = $data['urutan'] ?? (ProduksiStatus::max('urutan') + 1);

        ProduksiStatus::create($data);
        return redirect()->route('admin.produksi-status.index')->with('berhasil','Status dibuat.');
    }

    public function edit(ProduksiStatus $produksi_status)
    {
        return view('admin.produksi_status.edit', ['status' => $produksi_status]);
    }

    public function update(Request $r, ProduksiStatus $produksi_status)
    {
        $data = $r->validate([
            'key'      => ['required','alpha_dash','lowercase','max:50', Rule::unique('produksi_status','key')->ignore($produksi_status->id)],
            'label'    => ['required','string','max:100'],
            'urutan'   => ['nullable','integer','min:0'],
            'is_final' => ['nullable','boolean'],
        ]);
        $data['is_final'] = (bool)($data['is_final'] ?? false);

        $produksi_status->update($data);
        return redirect()->route('admin.produksi-status.index')->with('berhasil','Status diperbarui.');
    }

    public function destroy(ProduksiStatus $produksi_status)
    {
        // Cegah hapus jika masih dipakai oleh produksi
        $inUse = Produksi::where('status_key', $produksi_status->key)->exists();
        if ($inUse) {
            return back()->with('peringatan','Tidak dapat dihapus karena masih dipakai oleh data produksi.');
        }
        $produksi_status->delete();
        return redirect()->route('admin.produksi-status.index')->with('berhasil','Status dihapus.');
    }
}

