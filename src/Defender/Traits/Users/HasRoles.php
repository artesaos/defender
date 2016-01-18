<?php

namespace Artesaos\Defender\Traits\Users;

/**
 * Trait HasRoles.
 */
trait HasRoles
{
    /**
     * Returns true if the given user has any of the given roles.
     *
     * @param string|array $roles array or many strings of role name
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
     * Returns if the given user has an specific role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->roles
            ->where('name', $role)
            ->first() != null;
    }

    /**
     * Attach the given role.
     *
     * @param \Artesaos\Defender\Role $role
     */
    public function attachRole($role)
    {
        if (! $this->hasRole($role->name)) {
            $this->roles()->attach($role);
        }
    }

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
     * Detach the given role from the model.
     *
     * @param \Artesaos\Defender\Role $role
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

    /**
     * Take user by roles.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string|array $roles
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWhichRoles($query, $roles)
    {
        return $query->whereHas('roles', function ($query) use ($roles) {
            $roles = (is_array($roles)) ? $roles : [$roles];

            $query->whereIn('name', $roles);
        });
    }
}
