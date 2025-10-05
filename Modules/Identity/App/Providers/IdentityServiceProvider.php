<?php

declare(strict_types=1);

namespace Modules\Identity\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Identity\App\Models\User;
use Modules\Identity\App\Models\Classroom;
use Modules\Identity\App\Models\Series;

class IdentityServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        //
    ];

    /**
     * Indicates if events should be discovered.
     */
    protected static $eventsShouldDiscover = true;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/identity.php',
            'identity'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
        
        $this->loadFactoriesFrom(__DIR__ . '/../../Database/factories');
        
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'identity');

        // Register model observers if needed
        // User::observe(UserObserver::class);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
