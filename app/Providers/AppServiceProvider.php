<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

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
        Filament::registerNavigationGroups([
            'activities',
            'inventory settings',
            'stock initialization',
            'Inventory - Main warehouses',
            'Inventory - District warehouses',
            'access control'
        ]);
    }
}
