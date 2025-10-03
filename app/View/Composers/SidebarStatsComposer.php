<?php

namespace App\View\Composers;

use Illuminate\View\View;             // <- kelas View yang benar
use App\Models\Produksi;

class SidebarStatsComposer
{
    public function compose(View $view): void
    {
        $sidebarProses  = Produksi::whereIn('status_sekarang', ['Antri','Desain','Cetak','Finishing'])->count();
        $sidebarSelesai = Produksi::where('status_sekarang', 'Selesai')->count();

        $view->with(compact('sidebarProses', 'sidebarSelesai'));
    }
}
