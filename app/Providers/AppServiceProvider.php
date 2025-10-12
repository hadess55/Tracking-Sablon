<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Produksi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            [$sedangProses, $selesai] = Cache::remember('sidebar_produksi_stats', 30, function () {
                // pilih salah satu cara hitung di bawah sesuai skema kamu
                return [
                    Produksi::sedang()->count(),
                    Produksi::selesai()->count(),
                ];
            });

            $view->with(compact('sedangProses', 'selesai'));
        });

        Blade::component('layouts.auth', 'layouts.auth');

    }
    protected $policies = [
        \App\Models\Pesanan::class => \App\Policies\PesananPolicy::class,
    ];

}
