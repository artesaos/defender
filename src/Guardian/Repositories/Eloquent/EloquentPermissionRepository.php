<?php namespace Artesaos\Guardian\Repositories\Eloquent;

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

}