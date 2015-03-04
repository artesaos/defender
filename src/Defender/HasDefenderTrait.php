<?php namespace Artesaos\Defender;

use Illuminate\Database\Eloquent\Model;
use Artesaos\Defender\Pivots\PermissionUserPivot;

/**
 * Class HasDefenderTrait
 *
 * @package Artesaos\Defender
 */
trait HasDefenderTrait {

	/**
	 * Many-to-many role-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(
			config('defender.role_model'), config('defender.role_user_table'), 'user_id', config('defender.role_key')
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
			config('defender.permission_model'), config('defender.permission_user_table'), 'user_id', config('defender.permission_key')
		)->withPivot('value', 'expires');
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
	 * @param string $permission
	 * @param bool $inherit
	 * @return bool|null
	 */
	public function getPermission($permission, $inherit = true)
	{
		foreach ($this->permissions as $userPermission)
		{
			if ($userPermission->name === $permission)
			{
				if (is_null($userPermission->pivot->expires) or $userPermission->pivot->expires->isFuture())
				{
					return $userPermission->pivot->value;
				}
			}
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
		return $this->roles()->detach($role);
	}

	/**
	 * Sync the given roles
	 *
	 * @param array $roles
	 * @return array
	 */
	public function syncRoles(array $roles)
	{
		return $this->roles()->sync($roles);
	}

	/**
	 * Attach the given permission.
	 *
	 * @param array|Permission $permission
	 * @param array            $options
	 */
	public function attachPermission($permission, array $options = array())
	{
		return $this->permissions()->attach($permission, [
			'value'   => array_get($options, 'value', true),
			'expires' => array_get($options, 'expires', null)
		]);
	}

	/**
	 * Detach the given permission from the model.
	 *
	 * @param $permission
	 * @return int
	 */
	public function detachPermission($permission)
	{
		return $this->permissions()->detach($permission);
	}

	/**
	 * Sync the given permissions
	 *
	 * @param array $permissions
	 * @return array
	 */
	public function syncPermissions(array $permissions)
	{
		return $this->permissions()->sync($permissions);
	}

	/**
	 * Revoke all user permissions
	 *
	 * @return int
	 */
	public function revokePermissions()
	{
		return $this->permissions()->detach();
	}

	/**
	 * Revoke expired user permissions
	 *
	 * @return int|null
	 */
	public function revokeExpiredPermissions()
	{
		$expiredPermissions = $this->permissions()->expired()->get();

		if ($expiredPermissions->count() > 0)
		{
			return $this->permissions()->detach($expiredPermissions->modelKeys());
		}

		return null;
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
		$permissionModel = app()['config']->get('defender.permission_model');

		if ($parent instanceof $permissionModel)
		{
			return new PermissionUserPivot($parent, $attributes, $table, $exists);
		}

		return parent::newPivot($parent, $attributes, $table, $exists);
	}

}