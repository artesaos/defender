<?php namespace Artisans\Guardian\Providers;

use Artisans\Guardian\Role;
use Artisans\Guardian\Guardian;
use Artisans\Guardian\Permission;
use Illuminate\Support\ServiceProvider;
use Artisans\Guardian\Repositories\Eloquent\EloquentRoleRepository;
use Artisans\Guardian\Repositories\Eloquent\EloquentPermissionRepository;

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

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('guardian', function($app)
		{
			return new Guardian($app);
		});

		$this->registerEloquentBindings();
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
	 *
	 * @return [type] [description]
	 */
	protected function registerEloquentBindings()
	{

		$this->app->bind(
			'Artisans\Guardian\Repositories\RoleRepository',
			'Artisans\Guardian\Repositories\Eloquent\EloquentRoleRepository'
		);

		$this->app->bind(
			'Artisans\Guardian\Repositories\PermissionRepository',
			'Artisans\Guardian\Repositories\Eloquent\EloquentPermissionRepository'
		);

	}

}
