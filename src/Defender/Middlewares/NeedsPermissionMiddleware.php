<?php  namespace Artesaos\Defender\Middlewares;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class DefenderHasPermissionMiddleware
 * @package Artesaos\Defender
 */
class NeedsPermissionMiddleware {

	/**
	 * The current logged in user
	 *
	 * @var
	 */
	protected $user;

	/**
	 * @param Authenticatable $user
	 */
	public function __construct(Authenticatable $user)
	{
		$this->user = $user;
	}

	/**
	 * @param \Illuminate\Contracts\Http\Request $request
	 * @param callable $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$permissions   = $this->getPermission($request);
		$anyPermission = $this->getAny($request);

		if (is_array($permissions) and count($permissions) > 0)
		{
			$canResult = true;

			foreach($permissions as $permission)
			{
				$canPermission = $this->user->can($permission);

				// Check if any permission is enough
				if ($anyPermission and $canPermission)
				{
					return $next($request);
				}

				$canResult = $canResult & $canPermission;
			}

			if ( ! $canResult )
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
		$routeActions = $this->getActions($request);

		$permissions = array_get($routeActions, 'permissions', []);

		return is_array($permissions) ? $permissions : [ $permissions ];
	}

	/**
	 * @param $request
	 * @return mixed
	 */
	private function getAny($request)
	{
		$routeActions = $this->getActions($request);

		return array_get($routeActions, 'any', false);
	}

	/**
	 * @param $request
	 * @return mixed
	 */
	private function getActions($request)
	{
		$routeActions = $request->route()->getAction();

		return $routeActions;
	}
	
}