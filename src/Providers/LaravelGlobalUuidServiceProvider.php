<?php

namespace Submtd\LaravelGlobalUuid\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelGlobalUuidServiceProvider extends ServiceProvider
{
    /**
     * boot method
     * @return void
     */
    public function boot()
    {
        // set up config file
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-global-uuid.php', 'laravel-global-uuid');
        $this->publishes([__DIR__ . '/../../config' => config_path('middlehigh')], 'config');
        // set up migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->publishes([__DIR__ . '/../../database' => database_path()], 'migrations');
    }
}
