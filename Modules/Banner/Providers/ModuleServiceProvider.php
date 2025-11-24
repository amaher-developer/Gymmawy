<?php

namespace Modules\Banner\Providers;

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
        $this->loadTranslationsFrom(module_path('banner') . '/Resources/Lang', 'banner');
        $this->loadViewsFrom(module_path('banner') . '/Resources/Views', 'banner');
        $this->loadMigrationsFrom(module_path('banner') . '/Database/Migrations');
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
