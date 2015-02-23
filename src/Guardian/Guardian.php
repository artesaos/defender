<?php namespace Artisans\Guardian;

use Illuminate\Contracts\Foundation\Application;

/**
 *
 */
class Guardian {

	/**
	 * The Laravel Application
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Class constructor
	 *
	 * @param Application $app Laravel Application
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function user()
	{
		return $this->app['auth']->user();
	}

}