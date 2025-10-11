<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Produksi;

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
        // View::composer(['layouts.admin', 'partials.admin-sidebar'], function ($view) {
        //     $sedangProses = Produksi::sedang()->count();
        //     $selesai      = Produksi::selesai()->count();

        //     $view->with(compact('sedangProses', 'selesai'));
        // });
    }
}
