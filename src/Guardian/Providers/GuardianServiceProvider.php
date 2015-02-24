<?php namespace Artesaos\Guardian\Providers;

use Artesaos\Guardian\Guardian;
use Artesaos\Guardian\Permission;
use Artesaos\Guardian\Repositories\Eloquent\EloquentPermissionRepository;
use Artesaos\Guardian\Repositories\Eloquent\EloquentRoleRepository;
use Artesaos\Guardian\Role;
use Illuminate\Support\ServiceProvider;

class GuardianServiceProvider extends ServiceProvider {

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
		$this->app->singleton('guardian', function($app)
		{
			return new Guardian($app, $app['guardian.role'], $app['guardian.permission']);
		});

		$this->registerRepositoryInterfaces();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

	/**
	 * Bind repositories interfaces with their implementations
	 */
	protected function registerRepositoryInterfaces()
	{
		$this->app->bindShared('guardian.role', function($app)
		{
			return new EloquentRoleRepository($app, new Role);
		});

		$this->app->bindShared('Artesaos\Guardian\Repositories\RoleRepository', function($app)
		{
			return $app['guardian.role'];
		});

		$this->app->bindShared('guardian.permission', function ($app)
		{
			return new EloquentPermissionRepository($app, new Permission);
		});

		$this->app->bindShared('Artesaos\Guardian\Repositories\PermissionRepository', function($app)
		{
			return $app['guardian.permission'];
		});
	}

	/**
	 * Publish configuration file
	 */
	private function publishConfiguration()
	{
		$this->publishes([__DIR__ . '/../../resources/config/guardian.php' => config_path('guardian.php')], 'config');
	}

	private function publishMigrations()
	{
		$this->publishes([__DIR__ . '/../../resources/migrations/' => base_path('database/migrations')], 'migrations');
	}

}
