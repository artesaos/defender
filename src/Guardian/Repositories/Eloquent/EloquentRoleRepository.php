<?php namespace Artesaos\Guardian\Repositories\Eloquent;

use Artesaos\Guardian\Role;
use Artesaos\Guardian\Contracts\Repositories\RoleRepository;
use Illuminate\Contracts\Foundation\Application;

class EloquentRoleRepository extends AbstractEloquentRepository implements RoleRepository {

	public function __construct(Application $app, Role $model)
	{
		parent::__construct($app, $model);
	}

	public function findByName($roleName)
	{
		return $this->model->whereName($roleName)->first();
	}

}