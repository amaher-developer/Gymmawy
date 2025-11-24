<?php

namespace Modules\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'generic');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'generic');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
