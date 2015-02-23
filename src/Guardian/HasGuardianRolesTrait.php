<?php namespace Artisans\Guardian;

use Config;

trait HasGuardianRolesTrait {

	public function roles()
	{
		return $this->belongsToMany(Config::get('auth.model'));
	}

	public function hasRole()
	{

	}

	public function attachRole()
	{

	}

}