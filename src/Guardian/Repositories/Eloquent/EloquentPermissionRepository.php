<?php namespace Artisans\Guardian\Repositories\Eloquent;

use Artisans\Guardian\Permission;
use Artisans\Guardian\Repositories\PermissionRepository;

class EloquentPermissionRepository extends AbstractEloquentRepository implements PermissionRepository {

	public function __construct(Permission $model)
	{
		parent::__construct($model);
	}

}