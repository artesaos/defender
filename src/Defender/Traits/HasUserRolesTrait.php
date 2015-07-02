<?php

namespace Artesaos\Defender\Traits;

/**
 * Class HasUserRolesTrait.
 */
trait HasUserRolesTrait
{
    /**
     * Many-to-many role-user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $roleModel = config('defender.role_model', 'Artesaos\Defender\Role');
        $roleUserTable = config('defender.role_user_table', 'role_user');
        $roleKey = config('defender.role_key', 'role_id');

        return $this->belongsToMany($roleModel, $roleUserTable, 'user_id', $roleKey);
    }

    /**
     * Returns if the given user has an specific role.
     *
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        $roles = $this->roles->toBase()->lists('name')->toArray();

        return in_array($role, $roles);
    }

    /**
     * Returns true if the given user has any of the given roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function hasRoles($roles)
    {
        $roles = is_array($roles) ? $roles : func_get_args();

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Attach the given role.
     *
     * @param $role
     */
    public function attachRole($role)
    {
        if (!$this->hasRole($role)) {
            return $this->roles()->attach($role);
        }
    }

    /**
     * Detach the given role from the model.
     *
     * @param $role
     *
     * @return int
     */
    public function detachRole($role)
    {
        return $this->roles()->detach($role);
    }

    /**
     * Sync the given roles.
     *
     * @param array $roles
     *
     * @return array
     */
    public function syncRoles(array $roles)
    {
        return $this->roles()->sync($roles);
    }
}
