<?php namespace Artesaos\Guardian\Contracts\Repositories;

interface RoleRepository {

	public function findByName($roleName);

}