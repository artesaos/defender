<?php namespace Artesaos\Guardian;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HasGuardianTrait
 * @package Artesaos\Guardian
 */
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
		$roles = $this->roles->lists('name');

		return in_array($role, $roles);
	}

	/**
	 * Returns if the current user has the given permission.
	 * User permissions override role permissions.
	 *
	 * @param $permission
	 * @return bool
	 */
	public function can($permission)
	{
		$userPermission = $this->getPermission($permission);

		return is_null($userPermission) ? $this->canWithRolePermissions($permission) : $userPermission;
	}

	/**
	 * Check if the user has the given permission using
	 * only his roles.
	 *
	 * @param $permission
	 * @return bool
	 */
	public function canWithRolePermissions($permission)
	{
		// Search roles permission
		foreach ($this->roles as $role)
		{
			if ($rolePermission = $role->getPermission($permission))
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
	 * @return bool|null
	 */
	public function getPermission($permission, $inherit = true)
	{
		$userPermissions = $this->permissions->lists('pivot.value', 'name');

		if (array_key_exists($permission, $userPermissions))
		{
			return $userPermissions[$permission];
		}

		return $inherit ? null : false;
	}

	/**
	 * Attach the given role.
	 *
	 * @param $role
	 */
	public function attachRole($role)
	{
		$roleModel = app()['config']->get('guardian.role_model');

		if ($role instanceof $roleModel)
		{
			return $this->roles()->attach($role->id);
		}

		return $this->roles()->attach($role);
	}

	/**
	 * Detach the given role from the model.
	 *
	 * @param $role
	 * @return int
	 */
	public function detachRole($role)
	{
		$roleModel = app()['config']->get('guardian.role_model');

		if ($role instanceof $roleModel)
		{
			return $this->roles()->detach($role->id);
		}

		return $this->roles()->detach($role);
	}

	/**
	 * Attach the given permission.
	 *
	 * @param $permission
	 * @param $value
	 */
	public function attachPermission($permission, $value)
	{
		$permissionModel = app()['config']->get('guardian.permission_model');

		if ($permission instanceof $permissionModel)
		{
			return $this->permissions()->attach($permission->id, ['value' => $value]);
		}

		return $this->permissions()->attach($permission, ['value' => $value]);
	}

	/**
	 * Detach the given permission from the model.
	 *
	 * @param $permission
	 * @return int
	 */
	public function detachPermission($permission)
	{
		$permissionModel = app()['config']->get('guardian.permission_model');

		if ($permissionModel instanceof $permissionModel)
		{
			$this->permissions()->detach($permission->id);
		}

		return $this->permissions()->detach($permission);
	}

	/**
	 * @param Model $parent
	 * @param array $attributes
	 * @param $table
	 * @param $exists
	 * @return PermissionUserPivot
	 */
	public function newPivot(Model $parent, array $attributes, $table, $exists)
	{
		$permissionModel = app()['config']->get('guardian.permission_model');

		if ($parent instanceof $permissionModel)
		{
			return new PermissionUserPivot($parent, $attributes, $table, $exists);
		}

		return parent::newPivot($parent, $attributes, $table, $exists);
	}

}