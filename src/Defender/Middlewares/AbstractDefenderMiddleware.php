<?php  namespace Artesaos\Defender\Middlewares;

use Illuminate\Contracts\Auth\Authenticatable;

abstract class AbstractDefenderMiddleware {

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
	 * @param $request
	 * @return mixed
	 */
	protected function getAny($request)
	{
		$routeActions = $this->getActions($request);

		return array_get($routeActions, 'any', false);
	}

	/**
	 * @param $request
	 * @return mixed
	 */
	protected function getActions($request)
	{
		$routeActions = $request->route()->getAction();

		return $routeActions;
	}

	/**
	 * Handles the forbidden response
	 *
	 * @return mixed
	 */
	protected function forbiddenResponse()
	{
		return call_user_func(config('defender.forbidden_callback', function() {
			return response('Forbidden', 403);
		}));
	}

}