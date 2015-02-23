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

}