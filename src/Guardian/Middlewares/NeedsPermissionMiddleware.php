<?php  namespace Artesaos\Guardian\Middlewares;

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
	 * @param \Illuminate\Contracts\Http\Request $request
	 * @param callable $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$permission = $this->getPermission($request);

		if ($permission)
		{
			if ( ! $this->auth->user()->can($permission))
			{
				return response('Forbidden', 403); // TODO: Exception?
			}
		}

		return $next($request);
	}

	/**
	 * @param \Illuminate\Contracts\Http\Request $request
	 * @return mixed
	 */
	private function getPermission($request)
	{
		$routeActions = $request->route()->getAction();

		return array_get($routeActions, 'permission', false);
	}
	
}