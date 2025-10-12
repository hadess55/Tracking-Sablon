<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\Admin\PesananAdminController;
use App\Http\Controllers\Admin\CustomerAdminController;
use App\Http\Controllers\Admin\ProduksiAdminController;
use App\Http\Controllers\Admin\ProduksiStatusController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Admin\NotifController;

// Route::get('/', function () {
//     return view('public.home');
// });
Route::get('/', [TrackingController::class, 'index'])->name('home');


Route::middleware(['auth','admin'])->get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::get('/tracking/{resi}', [TrackingController::class, 'show'])->name('tracking.show');


Route::middleware('auth')->group(function () {
    // Customer
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/buat', [PesananController::class, 'buat'])->name('pesanan.buat');
    Route::post('/pesanan', [PesananController::class, 'simpan'])->name('pesanan.simpan');
    Route::get('/pesanan/{pesanan}', [PesananController::class,'tampil'])->name('pesanan.tampil');  
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin
    Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/produksi', [ProduksiAdminController::class,'index'])->name('produksi.index');
        Route::get('/produksi/{produksi}', [ProduksiAdminController::class,'show'])->name('produksi.show');
        Route::put('/produksi/{produksi}', [ProduksiAdminController::class,'update'])->name('produksi.update');
        Route::get('/produksi/quick', [ProduksiAdminController::class,'quick'])->name('produksi.quick');
        Route::resource('produksi-status', ProduksiStatusController::class)->except(['show']);
        
        Route::resource('pesanan', PesananAdminController::class)->parameters([
            'pesanan' => 'pesanan'
        ]);
        Route::post('/pesanan/{pesanan}/setujui', [PesananAdminController::class, 'setujui'])->name('pesanan.setujui');
        Route::post('/pesanan/{pesanan}/tolak', [PesananAdminController::class, 'tolak'])->name('pesanan.tolak');

        Route::resource('customer', CustomerAdminController::class)->parameters([
            'customer' => 'user'
        ])->except(['show']);

        Route::get('/notif/pesanan', [NotifController::class, 'pendingPesanan'])
        ->name('notif.pesanan');
        Route::get('/produksi/stats', [ProduksiAdminController::class,'stats'])
        ->name('produksi.stats');

    });
});

require __DIR__.'/auth.php';
