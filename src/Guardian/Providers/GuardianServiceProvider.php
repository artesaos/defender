<?php namespace Artisans\Guardian\Providers;

use Artisans\Guardian\Guardian;
use Artisans\Guardian\Permission;
use Artisans\Guardian\Repositories\Eloquent\EloquentPermissionRepository;
use Artisans\Guardian\Repositories\Eloquent\EloquentRoleRepository;
use Artisans\Guardian\Role;
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
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('guardian', function($app)
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
     * Bind repositories interfaces with their implementations
     */
	protected function registerEloquentBindings()
	{

		$this->app->bindShared('Artisans\Guardian\Repositories\RoleRepository', function($app)
        {
            return new EloquentRoleRepository($app, new Role);
        });

		$this->app->bindShared('Artisans\Guardian\Repositories\PermissionRepository', function($app)
        {
            return new EloquentPermissionRepository($app, new Permission);
        });

	}

    /**
     * Publish configuration file
     */
    private function publishConfiguration()
    {
        $this->publishes([ __DIR__.'/../../resources/config/guardian.php' => config_path('guardian.php')]);
    }

}
