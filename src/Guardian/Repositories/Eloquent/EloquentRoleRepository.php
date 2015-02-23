<?php namespace Artisans\Guardian\Repositories\Eloquent;

use Artisans\Guardian\Role;
use Artisans\Guardian\Contracts\Repositories\RoleRepository;
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