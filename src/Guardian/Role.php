<?php namespace Artesaos\Guardian;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package Artesaos\Guardian
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
		$this->table = config('guardian.role_table', 'roles');
	}

	/**
	 * Many-to-many role-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(
			config('auth.model'), config('guardian.role_user_table'), config('guardian.role_key'), 'user_id'
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
			config('guardian.permission_model'), config('guardian.permission_role_table'), config('guardian.role_key'), config('guardian.permission_key')
		)->withPivot('value');
	}

	/**
	 * Get role permission using the permission name
	 *
	 * @param $permission
	 * @return int|null
	 */
	public function getPermission($permission)
	{
		$rolePermissions = $this->permissions->lists('pivot.value', 'name');

		if (array_key_exists($permission, $rolePermissions))
		{
			return (int) $rolePermissions[$permission];
		}

		return null;
	}

}