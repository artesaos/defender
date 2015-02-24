<?php namespace Artesaos\Guardian;

trait HasGuardianTrait {

	/**
	 * Many-to-many role-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(
			config('guardian.role_model'), config('guardian.role_user_table'), 'user_id', config('guardian.role_key')
		);
	}

	/**
	 * Many-to-many permission-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function permissions()
	{
		return $this->belongsToMany(
			config('guardian.permission_model'), config('guardian.permission_user_table'), 'user_id', config('guardian.permission_key')
		)->withPivot('value');
	}

	/**
	 * Returns if the given user has an specific role
	 *
	 * @param $role
	 * @return bool
	 */
	public function hasRole($role)
	{
		$roles = $this->roles()->lists('name');

		return in_array($role, $roles);
	}

	/**
	 * @param $permission
	 * @return bool
	 */
	public function can($permission)
	{
		$userPermission = $this->getPermission($permission);

		// 0 = inherit from roles
		if ($userPermission !== 0)
		{
			return $userPermission > 0;
		}

		return $this->canWithRolesPermissions($permission);
	}

	/**
	 * Check if the user has the given permission using
	 * only his roles.
	 *
	 * @param $permission
	 * @return bool
	 */
	public function canWithRolesPermissions($permission)
	{
		// Search roles permission
		foreach ($this->roles as $role)
		{
			$rolePermission = $role->getPermission($permission);

			if ($rolePermission > 0)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the user permission using the permission name.
	 *
	 * @param $permission
	 * @param bool $inherit
	 * @return int|null
	 */
	public function getPermission($permission, $inherit = true)
	{
		$userPermissions = $this->permissions()->lists('value', 'name');

		if (array_key_exists($permission, $userPermissions))
		{
			return (int) $userPermissions[$permission];
		}

		return $inherit ? 0 : null;
	}

}