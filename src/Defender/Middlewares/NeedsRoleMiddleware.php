<?php  namespace Artesaos\Defender\Middlewares;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class DefenderHasPermissionMiddleware
 * @package Artesaos\Defender
 */
class NeedsRoleMiddleware extends AbstractDefenderMiddleware {

	/**
	 * @param \Illuminate\Contracts\Http\Request $request
	 * @param callable $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$roles = $this->getRoles($request);
		$anyRole = $this->getAny($request);

		if (is_null($this->user))
		{
			return response('Forbidden', 403); // TODO: Exception?
		}

		if (is_array($roles) and count($roles) > 0)
		{
			$hasResult = true;

			foreach ($roles as $role)
			{
				$hasRole = $this->user->hasRole($role);

				// Check if any role is enough
				if ($anyRole and $hasRole)
				{
					return $next($request);
				}

				$hasResult = $hasResult & $hasRole;
			}

			if ( ! $hasResult )
			{
				return response('Forbidden', 403); // TODO: Exception?
			}
		}

		return $next($request);
	}

	/**
	 * @param \Illuminate\Contracts\Http\Request $request
	 * @return array
	 */
	private function getRoles($request)
	{
		$routeActions = $this->getActions($request);

		$roles = array_get($routeActions, 'is', []);

		return is_array($roles) ? $roles : (array) $roles;
	}

}