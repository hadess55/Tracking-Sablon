<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\CustomerRegistrasiController;
use App\Http\Controllers\TrackingProduksiController;
use App\Http\Controllers\Admin\CustomerPersetujuanController;
use App\Http\Controllers\Admin\ProduksiController;
use App\Http\Controllers\Admin\RiwayatProduksiController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\TrackingController;

/* ---------- PUBLIC ---------- */
Route::get('/', [TrackingProduksiController::class, 'form'])->name('tracking.form');
Route::get('/lacak', [TrackingProduksiController::class, 'form'])->name('tracking');
Route::post('/lacak', [TrackingProduksiController::class, 'search']);

Route::get('/daftar', [CustomerRegistrasiController::class, 'form'])->name('customers.registrasi.form');
Route::post('/daftar', [CustomerRegistrasiController::class, 'simpan'])->name('customers.registrasi.simpan');

Route::get('/lacak/{nomor}', [TrackingController::class, 'show'])
     ->name('tracking.show');

/* ---------- ADMIN AUTH ---------- */
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/* ---------- ADMIN AREA (PROTECTED) ---------- */
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('pelanggan/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('pelanggan',        [CustomerController::class, 'store'])->name('customers.store');
    Route::get('pelanggan/{customer}',        [CustomerController::class, 'show'])->name('customers.show');
    Route::get('pelanggan/{customer}/edit',   [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('pelanggan/{customer}',        [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('pelanggan/{customer}',     [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('pelanggan', [CustomerPersetujuanController::class, 'index'])->name('customers.index');
    Route::get('/pelanggan/quick-search', [CustomerPersetujuanController::class, 'quick'])->name('customers.quick'); 
  

    Route::post('pelanggan/{customer}/setujui', [CustomerPersetujuanController::class, 'setujui'])->name('customers.setujui');
    Route::post('pelanggan/{customer}/tolak', [CustomerPersetujuanController::class, 'tolak'])->name('customers.tolak');

     Route::resource('produksi', ProduksiController::class)
         ->only(['index','create','store','show','edit','update', 'destroy']);

    Route::post('produksi/{produksi}/riwayat', [ProduksiController::class, 'tambahRiwayat'])
        ->name('produksi.riwayat.store');

    Route::get('/produksi/quick-search', [ProduksiController::class, 'quick'])
            ->name('produksi.quick');

    Route::get('/admin/notif/customers', [NotificationController::class, 'pendingCustomers'])
        ->name('admin.notif.customers');
    Route::get('/notif/customers', [NotificationController::class, 'pendingCustomers'])
            ->name('notif.customers');

    
});
