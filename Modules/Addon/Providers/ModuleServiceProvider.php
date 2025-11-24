<?php

namespace Modules\Addon\Providers;

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
        $this->loadTranslationsFrom(module_path('addon') . '/Resources/Lang', 'addon');
        $this->loadViewsFrom(module_path('addon') . '/Resources/Views', 'addon');
        $this->loadMigrationsFrom(module_path('addon') . '/Database/Migrations');
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
