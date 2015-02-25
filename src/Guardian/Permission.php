<?php namespace Artesaos\Guardian;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @package Artesaos\Guardian
 */
class Permission extends Model {

	/**
	 * @var
	 */
	protected $table;

	/**
	 * @var array
	 */
	protected $fillable = [
		'name',
		'display_name'
	];

	/**
	 * @param array $attributes
	 */
	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);
		$this->table = config('guardian.permission_table', 'permissions');
	}

	/**
	 * Many-to-many permission-role relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(
			config('guardian.role_model'), config('guardian.permission_role_table'), config('guardian.permission_key'), config('guardian.role_key')
		)->withPivot('value');
	}

	/**
	 * Many-to-many permission-user relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(
			config('auth.model'), config('guardian.permission_user_table'), config('guardian.permission_key'), 'user_id'
		)->withPivot('value');
	}

	/**
	 * @param Model $parent
	 * @param array $attributes
	 * @param string $table
	 * @param bool $exists
	 * @return PermissionUserPivot|\Illuminate\Database\Eloquent\Relations\Pivot
	 */
	public function newPivot(Model $parent, array $attributes, $table, $exists)
	{
		$userModel = app()['config']->get('auth.model');
		$roleModel = app()['config']->get('guardian.role_model');

		if ($parent instanceof $userModel)
		{
			return new PermissionUserPivot($parent, $attributes, $table, $exists);
		}

		if ($parent instanceof $roleModel)
		{
			return new PermissionRolePivot($parent, $attributes, $table, $exists);
		}

		return parent::newPivot($parent, $attributes, $table, $exists);
	}


}