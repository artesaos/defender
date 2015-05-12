<?php namespace Artesaos\Defender\Providers;

use Artesaos\Defender\Defender;
use Artesaos\Defender\Permission;
use Artesaos\Defender\Repositories\Eloquent\EloquentPermissionRepository;
use Artesaos\Defender\Repositories\Eloquent\EloquentRoleRepository;
use Artesaos\Defender\Role;
use Illuminate\Support\ServiceProvider;

class DefenderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishConfiguration();
		$this->publishMigrations();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('defender', function($app)
		{
			return new Defender($app, $app['defender.role'], $app['defender.permission']);
		});

		$this->registerRepositoryInterfaces();
		
        if ($this->app['config']->get('defender.template_helpers', true)) 
        {
            $this->registerBladeExtensions();
        }
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
	 * Bind repositories interfaces with their implementations
	 */
	protected function registerRepositoryInterfaces()
	{
		$this->app->bindShared('defender.role', function($app)
		{
			return new EloquentRoleRepository($app, new Role);
		});

		$this->app->bindShared('Artesaos\Defender\Repositories\RoleRepository', function($app)
		{
			return $app['defender.role'];
		});

		$this->app->bindShared('defender.permission', function ($app)
		{
			return new EloquentPermissionRepository($app, new Permission);
		});

		$this->app->bindShared('Artesaos\Defender\Repositories\PermissionRepository', function($app)
		{
			return $app['defender.permission'];
		});
	}
    
    /**
     * Register new blade extensions
     */ 
	protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function()
        {
            /**
             * add @can and @endcan to blade compiler
             */
            $this->app['blade.compiler']->extend(function($view, $compiler)
            {
                $open  = $compiler->createOpenMatcher('can');
                $close = $compiler->createPlainMatcher('endcan');

                $template = ['$1<?php if(app(\'defender\')->can$2)): ?>', '$1<?php endif; ?>'];

                return preg_replace([$open, $close], $template, $view);
            });

            /**
             * Add @is and @endis to blade compiler
             */
            $this->app['blade.compiler']->extend(function($view, $compiler)
            {
                $open  = $compiler->createOpenMatcher('is');
                $close = $compiler->createPlainMatcher('endis');

                $template = ['$1<?php if(app(\'defender\')->hasRole$2)): ?>', '$1<?php endif; ?>'];

                return preg_replace([$open, $close], $template, $view);
            });
        });
    }
    
	/**
	 * Publish configuration file
	 */
	private function publishConfiguration()
	{
		$this->publishes([__DIR__ . '/../../resources/config/defender.php' => config_path('defender.php')], 'config');
		$this->mergeConfigFrom(__DIR__ . '/../../resources/config/defender.php', 'defender');
	}

    /**
     * Publish migration file
     */
	private function publishMigrations()
	{
		$this->publishes([__DIR__ . '/../../resources/migrations/' => base_path('database/migrations')], 'migrations');
	}

}
