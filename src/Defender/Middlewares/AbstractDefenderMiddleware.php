<?php  namespace Artesaos\Defender\Middlewares;

abstract class AbstractDefenderMiddleware {

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

}