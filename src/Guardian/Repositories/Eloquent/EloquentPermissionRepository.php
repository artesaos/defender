<?php namespace Artisans\Guardian\Repositories\Eloquent;

use Artisans\Guardian\Permission;
use Artisans\Guardian\Contracts\Repositories\PermissionRepository;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class EloquentPermissionRepository
 *
 * @package Artisans\Guardian\Repositories\Eloquent
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