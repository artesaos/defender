<?php namespace Artisans\Guardian\Repositories\Eloquent;

use Artisans\Guardian\Role;
use Artisans\Guardian\Repositories\RoleRepository;

class EloquentRoleRepository extends AbstractEloquentRepository implements RoleRepository {

	public function __construct(Role $model)
	{
		parent::__construct($model);
	}

	public function findByName($roleName)
	{
		return $this->model->whereName($roleName)->first();
	}

}