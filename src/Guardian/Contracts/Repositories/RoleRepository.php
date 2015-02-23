<?php namespace Artisans\Guardian\Contracts\Repositories;

interface RoleRepository {

	public function findByName($roleName);

}