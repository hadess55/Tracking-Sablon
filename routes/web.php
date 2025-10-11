<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\Admin\PesananAdminController;
use App\Http\Controllers\Admin\CustomerAdminController;

Route::get('/', function () {
    return view('public.home');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Customer
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/buat', [PesananController::class, 'buat'])->name('pesanan.buat');
    Route::post('/pesanan', [PesananController::class, 'simpan'])->name('pesanan.simpan');
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'tampil'])->name('pesanan.tampil');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin
    Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('pesanan', PesananAdminController::class)->parameters([
            'pesanan' => 'pesanan'
        ]);
        Route::post('/pesanan/{pesanan}/setujui', [PesananAdminController::class, 'setujui'])->name('pesanan.setujui');
        Route::post('/pesanan/{pesanan}/tolak', [PesananAdminController::class, 'tolak'])->name('pesanan.tolak');
        Route::resource('customer', CustomerAdminController::class)->parameters([
            'customer' => 'user'
        ])->except(['show']);

    });
});

require __DIR__.'/auth.php';
