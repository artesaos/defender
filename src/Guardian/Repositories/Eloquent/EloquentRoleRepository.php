<?php namespace Artisans\Guardian\Repositories\Eloquent;

use Artisans\Guardian\Repositories\RoleRepository;

class EloquentRoleRepository extends AbstractEloquentRepository implements RoleRepository {

	public function findByName($roleName)
	{
		return $this->model->whereName($roleName)->first();
	}

}