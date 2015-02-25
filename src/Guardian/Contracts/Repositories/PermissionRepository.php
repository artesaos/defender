<?php namespace Artesaos\Guardian\Contracts\Repositories;

interface PermissionRepository extends AbstractRepository {

	public function create($permissionName, $displayName = null);

}