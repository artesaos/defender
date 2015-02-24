<?php namespace Artesaos\Guardian\Repositories\Eloquent;

use Artesaos\Guardian\Role;
use Artesaos\Guardian\Contracts\Repositories\RoleRepository;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class EloquentRoleRepository
 * @package Artesaos\Guardian\Repositories\Eloquent
 */
class EloquentRoleRepository extends AbstractEloquentRepository implements RoleRepository {

	/**
	 * @param Application $app
	 * @param Role $model
	 */
	public function __construct(Application $app, Role $model)
	{
		parent::__construct($app, $model);
	}

	/**
	 * Create a new role with the given name.
	 *
	 * @param $roleName
	 * @return static
	 * @throws \Exception
	 */
	public function create($roleName)
	{
		if ( ! is_null($this->findByName($roleName)))
		{
			// TODO: add translation support and custom exception
			throw new \Exception('A role with the given name already exists');
		}

		return $role = $this->model->newInstance(['name' => $roleName]);
	}

}