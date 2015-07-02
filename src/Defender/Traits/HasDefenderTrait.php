<?php

namespace Artesaos\Defender\Traits;

use Carbon\Carbon;

/**
 * Class HasDefenderTrait.
 */
trait HasDefenderTrait
{
    use HasUserRolesTrait, HasUserPermissionsTrait;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $_permissions;

    /**
     * Retrieve all user permissions
     *
     * @param bool $force
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions($force = false)
    {
        if(empty($this->_permissions) or true === $force)
        {
            $roles = $this->roles()->get()->lists('id')->toArray();

            $permissionsRoles = app('defender.permission')->getByRoles($roles)->toBase();

            $permissions =  $this->permissions()->wherePivot('value', true)->wherePivot('expires', '>=', Carbon::now())->get()->toBase();

            $permissions = $permissions->merge($permissionsRoles);

            $this->_permissions = $permissions->map(function($perm){

                unset($perm->pivot, $perm->created_at, $perm->updated_at);

                return $perm;
            });
        }

        return $this->_permissions;
    }

    /**
     * Check if the user has the given permission using
     * only his roles.
     *
     * @param $permission
     *
     * @return bool
     */
    public function canWithRolePermissions($permission)
    {
        // If has superuser role
        if ($this->hasRole(config('defender.superuser_role', 'superuser'))) {
            return true;
        }

        $permissions = $this->getPermissions()->lists('name')->toArray();

        return in_array($permission, $permissions);
    }


    /**
     * Returns if the current user has the given permission.
     * User permissions override role permissions.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can($permission)
    {
        return $this->canWithRolePermissions($permission);
    }
}
