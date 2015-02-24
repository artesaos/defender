<?php namespace Artesaos\Guardian\Contracts\Repositories;

interface PermissionRepository {

	public function create($permissionName);

	public function findByName($permissionName);

}