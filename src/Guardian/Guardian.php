<?php namespace Artesaos\Guardian;

use Artesaos\Guardian\Contracts\Repositories\PermissionRepository;
use Artesaos\Guardian\Contracts\Repositories\RoleRepository;
use Illuminate\Contracts\Foundation\Application;

/**
 *
 */
class Guardian {

	/**
	 * The Laravel Application
	 *
	 * @var \Illuminate\Contracts\Foundation\Application
	 */
	protected $app;

	/**
	 * @var RoleRepository
	 */
	private $roleRepository;

	/**
	 * Class constructor
	 *
	 * @param Application $app Laravel Application
	 * @param RoleRepository $roleRepository
	 * @param PermissionRepository $permissionRepository
	 */
	public function __construct(Application $app, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
	{
		$this->app = $app;
		$this->roleRepository = $roleRepository;
		$this->permissionRepository = $permissionRepository;
	}

	/**
	 * [user description]
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function getUser()
	{
		return $this->app['auth']->user();
	}

	/**
	 * @param $permission
	 * @return bool
	 */
	public function can($permission)
	{
		if ( ! is_null($this->getUser()))
		{
			return $this->getUser()->can($permission);
		}

		return false;
	}

	/**
	 * Create a new role.
	 * Uses a repository to actually create the role.
	 *
	 * @param $roleName
	 * @return mixed
	 */
	public function createRole($roleName)
	{
		return $this->roleRepository->create($roleName);
	}

	/**
	 * @param $permissionName
	 * @return mixed
	 */
	public function createPermission($permissionName)
	{
		return $this->permissionRepository->create($permissionName);
	}

}