<?php namespace Artesaos\Defender;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package Artesaos\Defender
 */
class Role extends Model {

	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * Mass-assignment whitelist
	 *
	 * @var array
	 */
	protected $fillable = [
		'name'
	];

	/**
	 * @param array $attributes
	 */
	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);
		$this->table = config('defender.role_table', 'roles');
	}

	/**
	 * Many-to-many role-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(
			config('auth.model'), config('defender.role_user_table'), config('defender.role_key'), 'user_id'
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
			config('defender.permission_model'), config('defender.permission_role_table'), config('defender.role_key'), config('defender.permission_key')
		)->withPivot('value');
	}

	/**
	 * Get role permission using the permission name
	 *
	 * @param $permission
	 * @return bool
	 */
	public function getPermission($permission)
	{
		$rolePermissions = $this->permissions->lists('pivot.value', 'name');

		if (array_key_exists($permission, $rolePermissions))
		{
			return $rolePermissions[$permission];
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