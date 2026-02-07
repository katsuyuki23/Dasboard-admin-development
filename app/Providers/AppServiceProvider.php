<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        if (str_contains(request()->header('Host'), 'ngrok')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \App\Models\TransaksiKas::observe(\App\Observers\TransaksiKasObserver::class);
    }
}
