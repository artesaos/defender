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
	 * @param Guard $auth
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function user(Guard $auth)
	{
		return $auth->user();
	}

	public function can()
	{
		return true;
	}

}