<?php

namespace Artesaos\Defender\Middlewares;

use Closure;

/**
 * Class DefenderHasPermissionMiddleware.
 */
class NeedsRoleMiddleware extends AbstractDefenderMiddleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param callable                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null, $any = false)
    {
        if (is_null($roles)) {
            $roles = $this->getRoles($request);
            $anyRole = $this->getAny($request);
        } else {
            $roles = explode('|', $roles); // Laravel 5.1 - Using parameters
        }

        if (is_null($this->user)) {
            return $this->forbiddenResponse();
        }

        if (is_array($roles) and count($roles) > 0) {
            $hasResult = true;

            foreach ($roles as $role) {
                $hasRole = $this->user->hasRole($role);

                // Check if any role is enough
                if ($anyRole and $hasRole) {
                    return $next($request);
                }

                $hasResult = $hasResult & $hasRole;
            }

            if (!$hasResult) {
                return $this->forbiddenResponse();
            }
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    private function getRoles($request)
    {
        $routeActions = $this->getActions($request);

        $roles = array_get($routeActions, 'is', []);

        return is_array($roles) ? $roles : (array) $roles;
    }
}
