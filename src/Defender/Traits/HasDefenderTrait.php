<?php

namespace Artesaos\Defender\Traits;

use Illuminate\Support\Collection;

/**
 * Class HasDefenderTrait.
 */
trait HasDefenderTrait
{
    use HasUserRolesTrait, HasUserPermissionsTrait;


    /**
     * Retrieve all user permissions
     *
     * @return Collection
     */
    public function getPermissions()
    {
        $roles = $this->roles()->get()->lists('id')->toArray();

        $permissionsRoles = app('defender.permission')->getByRoles($roles)->toBase();

        $permissions =  $this->permissions()->get()->toBase()->merge($permissionsRoles);

        return $permissions->map(function ($perm) {

            unset($perm->pivot, $perm->created_at, $perm->updated_at);

            return $perm;
        });
    }
}
