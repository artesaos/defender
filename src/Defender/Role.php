<?php namespace Artesaos\Defender;

use Illuminate\Database\Eloquent\Model;
use Artesaos\Defender\Traits\HasRolePermissionsTrait;

/**
 * Class Role
 * @package Artesaos\Defender
 */
class Role extends Model {

	use HasRolePermissionsTrait;

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

}