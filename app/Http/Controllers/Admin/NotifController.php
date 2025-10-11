<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Support\Str;

class NotifController extends Controller
{
    public function pendingPesanan()
    {
        $q = Pesanan::with('pengguna:id,name,email')
            ->where('status', 'menunggu')
            ->latest();

        $count = (clone $q)->count();

        $items = $q->limit(8)->get()->map(function ($p) {
            return [
                'id'    => $p->id,
                'nama'  => $p->pengguna->name ?? 'Pengguna',
                'email' => $p->pengguna->email ?? '-',
                'since' => optional($p->created_at)->diffForHumans(), 
                'url'   => route('admin.pesanan.show', $p),   
            ];
        });

        return response()->json([
            'pending' => $count,
            'items'   => $items,
        ]);
    }
}
