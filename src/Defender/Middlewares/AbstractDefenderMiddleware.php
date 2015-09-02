<?php

namespace Artesaos\Defender\Middlewares;

use Artesaos\Defender\Contracts\ForbiddenHandler;

/**
 * Class AbstractDefenderMiddleware.
 */
abstract class AbstractDefenderMiddleware
{
    /**
     * The current logged in user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    public function __construct()
    {
        $this->user = app('defender')->getUser();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function getAny($request)
    {
        $routeActions = $this->getActions($request);

        return array_get($routeActions, 'any', false);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function getActions($request)
    {
        $routeActions = $request->route()->getAction();

        return $routeActions;
    }

    /**
     * Handles the forbidden response.
     *
     * @return mixed
     */
    protected function forbiddenResponse()
    {
        $handler = app()->make(config('defender.forbidden_callback'));

        return ($handler instanceof ForbiddenHandler) ? $handler->handle() : response('Forbidden', 403);
    }
}
