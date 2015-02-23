<?php namespace Artisans\Guardian\Providers;

use Artisans\Guardian\Guardian;
use Illuminate\Support\ServiceProvider as Provider;

class GuardianServiceProvider extends Provider {

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

}
