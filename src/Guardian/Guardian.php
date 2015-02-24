<?php namespace Artesaos\Guardian;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;

/**
 *
 */
class Guardian {

	/**
	 * The Laravel Application
	 *
	 * @var \Illuminate\Contracts\Foundation\Application
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

	/**
	 * [user description]
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function user()
	{
		return $this->app['auth']->user();
	}

	/**
	 * @param $permission
	 * @return bool
	 */
	public function can($permission)
	{
		if ( ! is_null($this->user()))
		{
			return $this->user()->can($permission);
		}

		return false;
	}

}