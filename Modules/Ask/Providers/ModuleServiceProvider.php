<?php

namespace Modules\Ask\Providers;

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
        $this->loadTranslationsFrom(module_path('ask') . '/Resources/Lang', 'ask');
        $this->loadViewsFrom(module_path('ask') . '/Resources/Views', 'ask');
        $this->loadMigrationsFrom(module_path('ask') . '/Database/Migrations');
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
