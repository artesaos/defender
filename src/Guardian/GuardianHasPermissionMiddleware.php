<?php  namespace Artesaos\Guardian;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class GuardianHasPermissionMiddleware
 * @package Artesaos\Guardian
 */
class GuardianHasPermissionMiddleware {

	/**
	 * @var
	 */
	protected $auth;

	/**
	 * @param Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * @param $request
	 * @param callable $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		return $next($request);
	}
	
}