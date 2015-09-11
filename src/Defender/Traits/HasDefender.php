<?php

namespace Artesaos\Defender\Traits;

use Carbon\Carbon;
use Artesaos\Defender\Traits\Users\HasRoles;
use Artesaos\Defender\Traits\Users\HasPermissions;

/**
 * Trait HasDefender.
 */
trait HasDefender
{
    use HasRoles, HasPermissions;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $cachedPermissions;

    /**
     * Returns if the current user has the given permission.
     * User permissions override role permissions.
     *
     * @param string $permission
     * @param bool   $force
     *
     * @return bool
     */
    public function hasPermission($permission, $force = false)
    {
        return $this->roleHasPermission($permission, $force);
    }

    /**
     * Check if the user has the given permission using
     * only his roles.
     *
     * @param string $permission
     * @param bool   $force
     *
     * @return bool
     */
    public function roleHasPermission($permission, $force = false)
    {
        // If has superuser role
        if ($this->hasRole(config('defender.superuser_role', 'superuser'))) {
            return true;
        }

        $permissions = $this->getPermissions($force)->lists('name')->toArray();

        return in_array($permission, $permissions);
    }

    /**
     * Retrieve all user permissions.
     *
     * @param bool $force
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions($force = false)
    {
        if (empty($this->cachedPermissions) or $force) {
            $this->cachedPermissions = $this->getFreshPermissions();
        }

        return $this->cachedPermissions;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getRolesPermissions()
    {
        $roles = $this->roles()->get(['id'])->lists('id')->toArray();

        return app('defender.permission')->getByRoles($roles);
    }

    /**
     * Get fresh permissions from database.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFreshPermissions()
    {
        $permissionsRoles = $this->getRolesPermissions();

        $permissions = app('defender.permission')->getActivesByUser($this);

        $permissions = $permissions->merge($permissionsRoles)
            ->map(function ($permission) {
                unset($permission->pivot, $permission->created_at, $permission->updated_at);

                return $permission;
            });

        return $permissions->toBase();
    }

    /**
     * Find a user by its id.
     *
     * @param int $id
     *
     * @return \Artesaos\Defender\Contracts\User
     */
    public function findById($id)
    {
        return $this->find($id);
    }
}
