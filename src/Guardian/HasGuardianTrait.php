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
		$userPermissions = $this->permissions()->lists('value', 'name');

		// Search in user permissions
		if (array_key_exists($permission, $userPermissions))
		{
			$permissionValue = (int) $userPermissions[$permission];

			if ($permissionValue !== 0)
			{
				return $permissionValue === 1 ? true : false;
			}
		}

		// Search roles permission
		$this->roles->each(function($role) use ($permission)
		{
			$rolePermissions = $role->permissions()->lists('value', 'name');

			if (array_key_exists($permission, $rolePermissions))
			{
				$permissionValue = (int) $rolePermissions[$permission];

				if ($permissionValue === 1)
				{
					return true;
				}
			}

		});

		return false;
	}

}