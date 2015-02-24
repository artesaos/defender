<?php namespace Artesaos\Guardian\Repositories\Eloquent;

use Artesaos\Guardian\Exceptions\PermissionExistsException;
use Artesaos\Guardian\Permission;
use Artesaos\Guardian\Contracts\Repositories\PermissionRepository;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class EloquentPermissionRepository
 *
 * @package Artesaos\Guardian\Repositories\Eloquent
 */
class EloquentPermissionRepository extends AbstractEloquentRepository implements PermissionRepository {

	/**
	 * @param Application $app
	 * @param Permission  $model
	 */
	public function __construct(Application $app, Permission $model)
	{
		parent::__construct($app, $model);
	}

	/**
	 * Create a new permission using the given name
	 *
	 * @param $permissionName
	 * @return static
	 * @throws \Exception
	 */
	public function create($permissionName)
	{
		if ( ! is_null($this->findByName($permissionName)))
		{
			// TODO: add translation support
			throw new PermissionExistsException('A permission with the given name already exists');
		}

		return $permission = $this->model->create(['name' => $permissionName]);
	}

}