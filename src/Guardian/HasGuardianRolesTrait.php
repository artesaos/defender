<?php namespace Artisans\Guardian;

use Config;

trait HasGuardianRolesTrait {

    /**
     * Many-to-many role-user relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function roles()
	{
		return $this->belongsToMany(Config::get('guardian.role_model'));
	}

    /**
     * Many-to-many permission-user relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Config::get('guardian.permission_model'));
    }



}