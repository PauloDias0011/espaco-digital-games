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
        // Temporariamente desabilitado para evitar dependência do Redis
        // if ($this->app->environment('local')) {
        //     $this->app->register(\Laravel\Horizon\HorizonServiceProvider::class);
        // }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
