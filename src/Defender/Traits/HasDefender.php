<?php

namespace Artesaos\Defender\Traits;

use Artesaos\Defender\Traits\Users\HasPermissions;
use Artesaos\Defender\Traits\Users\HasRoles;
use Carbon\Carbon;

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
     *
     * @return bool
     */
    public function can($permission)
    {
        return $this->canWithRolePermissions($permission);
    }

    /**
     * Check if the user has the given permission using
     * only his roles.
     *
     * @param string $permission
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
     * Get fresh permissions from database.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFreshPermissions()
    {
        $roles = $this->roles()->get()->lists('id')->toArray();

        $permissionsRoles = app('defender.permission')->getByRoles($roles);

        $table = $this->permissions()->getTable();

        $permissions = $this->permissions()
            ->where($table.'.value', true)
            ->where(function ($q) use ($table) {
                $q->where($table.'.expires', '>=', Carbon::now());
                $q->orWhereNull($table.'.expires');
            })
            ->get();

        $permissions = $permissions->merge($permissionsRoles)
            ->map(function ($permission) {
                unset($permission->pivot, $permission->created_at, $permission->updated_at);

                return $permission;
            });

        return $permissions->toBase();
    }
}
