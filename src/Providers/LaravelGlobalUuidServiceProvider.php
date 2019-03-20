<?php

namespace Submtd\LaravelGlobalUuid\Providers;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

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
        // optimize uuids
        $this->optimizeUuids();
    }

    protected function optimizeUuids()
    {
        $factory = new UuidFactory();
        $codec = new OrderedTimeCodec($factory->getUuidBuilder());
        $factory->setCodec($codec);
        Uuid::setFactory($factory);
    }
}
