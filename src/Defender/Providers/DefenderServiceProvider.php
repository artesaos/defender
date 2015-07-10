<?php

namespace Artesaos\Defender\Providers;

use Artesaos\Defender\Defender;
use Artesaos\Defender\Javascript;
use Artesaos\Defender\Permission;
use Artesaos\Defender\Repositories\Eloquent\EloquentPermissionRepository;
use Artesaos\Defender\Repositories\Eloquent\EloquentRoleRepository;
use Artesaos\Defender\Role;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

/**
 * Class DefenderServiceProvider.
 */
class DefenderServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     */
    public function boot()
    {
        $this->publishResources();
        $this->publishMigrations();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('defender', function ($app) {
            return new Defender($app, $app['defender.role'], $app['defender.permission']);
        });

        $this->app->singleton('defender.auth', function ($app) {
            return $app['auth'];
        });

        $this->app->singleton('defender.javascript', function ($app) {
            return new Javascript($app['defender']);
        });

        $this->app->alias('defender', 'Artesaos\Defender\Contracts\Defender');

        $this->app->alias('defender.javascript', 'Artesaos\Defender\Contracts\Javascript');

        $this->registerRepositoryInterfaces();

        $this->registerBladeExtensions();

        $this->loadHelpers();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['defender', 'defender.role', 'defender.permission'];
    }

    /**
     * Bind repositories interfaces with their implementations.
     */
    protected function registerRepositoryInterfaces()
    {
        $this->app->singleton('defender.role', function ($app) {
            return new EloquentRoleRepository($app, new Role());
        });

        $this->app->singleton('Artesaos\Defender\Contracts\Repositories\RoleRepository', function ($app) {
            return $app['defender.role'];
        });

        $this->app->singleton('defender.permission', function ($app) {
            return new EloquentPermissionRepository($app, new Permission());
        });

        $this->app->singleton('Artesaos\Defender\Contracts\Repositories\PermissionRepository', function ($app) {
            return $app['defender.permission'];
        });
    }

    /**
     * Register new blade extensions.
     */
    protected function registerBladeExtensions()
    {
        if (false === $this->app['config']->get('defender.template_helpers', true)) {
            return;
        }

        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            /*
             * add @can and @endcan to blade compiler
             */
            $bladeCompiler->directive('can', function ($expression) {
                return "<?php if(app('defender')->can{$expression}): ?>";
            });

            $bladeCompiler->directive('endcan', function ($expression) {
                return '<?php endif; ?>';
            });

            /*
             * add @is and @endis to blade compiler
             */
            $bladeCompiler->directive('is', function ($expression) {
                return "<?php if(app('defender')->hasRoles{$expression}): ?>";
            });

            $bladeCompiler->directive('endis', function ($expression) {
                return '<?php endif; ?>';
            });
        });
    }

    /**
     * Publish configuration file.
     */
    private function publishResources()
    {
        $this->publishes([__DIR__.'/../../resources/config/defender.php' => config_path('defender.php')], 'config');
        $this->mergeConfigFrom(__DIR__.'/../../resources/config/defender.php', 'defender');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'defender');
    }

    /**
     * Publish migration file.
     */
    private function publishMigrations()
    {
        $this->publishes([__DIR__.'/../../resources/migrations/' => base_path('database/migrations')], 'migrations');
    }

    /**
     * Load the helpers file.
     */
    private function loadHelpers()
    {
        if ($this->app['config']->get('defender.helpers', true)) {
            /*
             * That needs to be required inside that condition,
             * otherwise will have a side effect, will be always required.
             */
            require_once __DIR__.'/../helpers.php';
        }
    }
}
