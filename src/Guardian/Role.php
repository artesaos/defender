<?php namespace Artisans\Guardian;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package Artisans\Guardian
 */
class Role extends Model {

	/**
	 * @var string
	 */
	protected $table;

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

}