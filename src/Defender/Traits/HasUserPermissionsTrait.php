<?php

namespace Artesaos\Defender\Traits;

use Artesaos\Defender\Pivots\PermissionUserPivot;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HasUserPermissionsTrait.
 */
trait HasUserPermissionsTrait
{
    /**
     * Many-to-many permission-user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            config('defender.permission_model'), config('defender.permission_user_table'), 'user_id', config('defender.permission_key')
        )->withPivot('value', 'expires');
    }

    /**
     * Returns if the current user has the given permission.
     * User permissions override role permissions.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can($permission)
    {
        $userPermission = $this->getPermission($permission);

        return is_null($userPermission) ? $this->canWithRolePermissions($permission) : $userPermission;
    }

    /**
     * Get the user permission using the permission name.
     *
     * @param string $permission
     * @param bool   $inherit
     *
     * @return bool|null
     */
    public function getPermission($permission, $inherit = true)
    {
        foreach ($this->permissions as $userPermission) {
            if ($userPermission->name === $permission) {
                if (is_null($userPermission->pivot->expires) or $userPermission->pivot->expires->isFuture()) {
                    return $userPermission->pivot->value;
                }
            }
        }

        return $inherit ? null : false;
    }

    /**
     * Attach the given permission.
     *
     * @param array|Permission $permission
     * @param array            $options
     */
    public function attachPermission($permission, array $options = [])
    {
        return $this->permissions()->attach($permission, [
            'value'   => array_get($options, 'value', true),
            'expires' => array_get($options, 'expires', null),
        ]);
    }

    /**
     * Detach the given permission from the model.
     *
     * @param $permission
     *
     * @return int
     */
    public function detachPermission($permission)
    {
        return $this->permissions()->detach($permission);
    }

    /**
     * Sync the given permissions.
     *
     * @param array $permissions
     *
     * @return array
     */
    public function syncPermissions(array $permissions)
    {
        return $this->permissions()->sync($permissions);
    }

    /**
     * Revoke all user permissions.
     *
     * @return int
     */
    public function revokePermissions()
    {
        return $this->permissions()->detach();
    }

    /**
     * Revoke expired user permissions.
     *
     * @return int|null
     */
    public function revokeExpiredPermissions()
    {
        $expiredPermissions = $this->permissions()->wherePivot('expires', '<', Carbon::now())->get();

        if ($expiredPermissions->count() > 0) {
            return $this->permissions()->detach($expiredPermissions->modelKeys());
        }

        return;
    }

    /**
     * @param Model $parent
     * @param array $attributes
     * @param $table
     * @param $exists
     *
     * @return PermissionUserPivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        $permissionModel = app()['config']->get('defender.permission_model');

        if ($parent instanceof $permissionModel) {
            return new PermissionUserPivot($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
