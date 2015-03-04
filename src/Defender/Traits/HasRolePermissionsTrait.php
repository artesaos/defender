<?php  namespace Artesaos\Defender\Traits;

use Artesaos\Defender\Pivots\PermissionRolePivot;

/**
 * Class HasRolePermissionsTrait
 *
 * @package Artesaos\Defender\Traits
 */
trait HasRolePermissionsTrait {

	/**
	 * Many-to-many permission-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function permissions()
	{
		return $this->belongsToMany(
			config('defender.permission_model'), config('defender.permission_role_table'), config('defender.role_key'), config('defender.permission_key')
		)->withPivot('value', 'expires');
	}

	/**
	 * Attach permission
	 *
	 * @param       $permission
	 * @param array $options
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
	 * Sync permissions
	 *
	 * @param array $permissions
	 * @return array
	 */
	public function syncPermissions(array $permissions)
	{
		return $this->permissions()->sync($permissions);
	}

	/**
	 * Revoke all role permissions
	 *
	 * @return int
	 */
	public function revokePermissions()
	{
		return $this->permissions()->detach();
	}

	/**
	 * Revoke expired role permissions
	 *
	 * @return int|null
	 */
	public function revokeExpiredPermissions()
	{
		$expiredPermissions = $this->permissions()->wherePivot('expires', '<', Carbon::now())->get();

		if ($expiredPermissions->count() > 0)
		{
			return $this->permissions()->detach($expiredPermissions->modelKeys());
		}

		return null;
	}

	/**
	 * Get role permission using the permission name
	 *
	 * @param $permission
	 * @return bool
	 */
	public function getPermission($permission)
	{
		foreach ($this->permissions as $rolePermission)
		{
			if ($rolePermission->name === $permission)
			{
				if (is_null($rolePermission->pivot->expires) or $rolePermission->pivot->expires->isFuture())
				{
					return $rolePermission->pivot->value;
				}
			}
		}

		return false;
	}

	/**
	 * @param Model $parent
	 * @param array $attributes
	 * @param string $table
	 * @param bool $exists
	 * @return PermissionRolePivot|\Illuminate\Database\Eloquent\Relations\Pivot
	 */
	public function newPivot(Model $parent, array $attributes, $table, $exists)
	{
		$permissionModel = app()['config']->get('defender.permission_model');

		if ($parent instanceof $permissionModel)
		{
			return new PermissionRolePivot($parent, $attributes, $table, $exists);
		}

		return parent::newPivot($parent, $attributes, $table, $exists);
	}

}