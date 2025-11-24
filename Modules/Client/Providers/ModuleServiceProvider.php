<?php

namespace Modules\Client\Providers;

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
        $this->loadTranslationsFrom(module_path('client') . '/Resources/Lang', 'client');
        $this->loadViewsFrom(module_path('client') . '/Resources/Views', 'client');
        $this->loadMigrationsFrom(module_path('client') . '/Database/Migrations');
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
