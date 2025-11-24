<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

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
        // Fix for "Specified key was too long" error in MySQL
        Schema::defaultStringLength(191);
        
        // Use Bootstrap 5 pagination views instead of Tailwind
        
        Paginator::useBootstrap();
    }
}
