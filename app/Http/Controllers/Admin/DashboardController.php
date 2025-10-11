<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produksi;
use App\Models\ProduksiLog;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan Pesanan
        $menunggu = Pesanan::where('status', 'menunggu')->count();
        $disetujui = Pesanan::where('status', 'disetujui')->count();
        $ditolak   = Pesanan::where('status', 'ditolak')->count();
        $totalPesanan = Pesanan::count();

        // Ringkasan Produksi
        // → pilih salah satu sesuai skema Anda:
        // Jika pakai status_key 'selesai'
        $sedangProses = Produksi::where('status_key', '!=', 'selesai')->count();
        $selesai      = Produksi::where('status_key', 'selesai')->count();

        // Jika pakai relasi statusDef + is_final:
        // $sedangProses = Produksi::whereHas('statusDef', fn($q) => $q->where('is_final', false))->count();
        // $selesai      = Produksi::whereHas('statusDef', fn($q) => $q->where('is_final', true))->count();

        $totalProduksi = Produksi::count();

        // Data tabel/daftar
        $recentPesanan = Pesanan::with('pengguna:id,name,email')
            ->latest()->limit(6)->get();

        $recentLogs = ProduksiLog::with(['produksi:id,nomor_resi', 'author:id,name'])
            ->latest()->limit(8)->get();

        $totalCustomer = User::count(); // jika user = customer

        return view('admin.dashboard', compact(
            'menunggu','disetujui','ditolak','totalPesanan',
            'sedangProses','selesai','totalProduksi',
            'recentPesanan','recentLogs','totalCustomer'
        ));
    }
}

