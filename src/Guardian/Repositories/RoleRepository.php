<?php namespace Artisans\Guardian\Repositories;

interface RoleRepository {

	public function findByName($roleName);

}