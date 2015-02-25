<?php namespace Artesaos\Guardian\Contracts\Repositories;

interface RoleRepository extends AbstractRepository {

	public function create($roleName);

}