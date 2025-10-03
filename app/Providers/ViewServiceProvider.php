<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;   // <- Facade yang benar

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(
            'partials.admin-sidebar',
            \App\View\Composers\SidebarStatsComposer::class
        );
    }
}
