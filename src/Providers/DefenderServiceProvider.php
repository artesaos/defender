<?php

namespace Artesaos\Defender\Providers;

use Illuminate\Support\ServiceProvider;
use Artesaos\Defender\Contracts\Permissions\Resources\Collection as CollectionContract;
use Artesaos\Defender\Permissions\Resources\Collection as ResourcesCollection;

class DefenderServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishResources();
    }

    public function register()
    {
        $this->registerPermissions();
    }

    /**
     * Register permissions
     */
    private function registerPermissions()
    {
        $this->app->singleton(CollectionContract::class, function () {
            $resources = config('defender.resources', []);
            $collection = new ResourcesCollection();

            foreach ($resources as $resource) {
                $collection->add($resource);
            }

            return $collection;
        });
    }

    /**
     * Publish configuration file.
     */
    private function publishResources()
    {
        $dir = __DIR__;

        $this->publishes([$dir . '/../resources/config/defender.php' => config_path('defender.php')], 'config');
    }

    public function provides()
    {
        return [CollectionContract::class];
    }
}