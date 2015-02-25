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
	 * The RoleRepository implementation
	 *
	 * @var RoleRepository
	 */
	private $roleRepository;

	/**
	 * The PermissionRepository implementation
	 *
	 * @var PermissionRepository
	 */
	protected $permissionRepository;

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
	 * Get the current authenticated user
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function getUser()
	{
		return $this->app['auth']->user();
	}

	/**
	 * Check if the authenticated user can has the given permission
	 *
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
	 * Return if the authenticated user has the given role
	 *
	 * @param $roleName
	 * @return bool
	 */
	public function hasRole($roleName)
	{
		if ( ! is_null($this->getUser()))
		{
			return $this->getUser()->hasRole($roleName);
		}

		return false;
	}

	/**
	 * Check if a role with the given name exists.
	 *
	 * @param $roleName
	 * @return bool
	 */
	public function roleExists($roleName)
	{
		return $this->roleRepository->findByName($roleName) !== null;
	}

	/**
	 * Check if a permission with the given name exists.
	 *
	 * @param $permissionName
	 * @return bool
	 */
	public function permissionExists($permissionName)
	{
		return $this->permissionRepository->findByName($permissionName) !== null;
	}

	/**
	 * Get the role with the given name.
	 *
	 * @param $roleName
	 * @return \Artesaos\Guardian\Role|null
	 */
	public function findRole($roleName)
	{
		return $this->roleRepository->findByName($roleName);
	}

	public function findRoleById($roleId)
	{
		return $this->roleRepository->findById($roleId);
	}

	/**
	 * Get the permission with the given name
	 *
	 * @param $permissionName
	 * @return \Artesaos\Guardian\Permission|null
	 */
	public function getPermission($permissionName)
	{
		return $this->permissionRepository->findByName($permissionName);
	}

	public function permissionsList()
	{
		return $this->permissionRepository->getList('name', 'id');
	}

	/**
	 * Create a new role.
	 * Uses a repository to actually create the role.
	 *
	 * @param $roleName
	 * @return \Artesaos\Guardian\Role
	 */
	public function createRole($roleName)
	{
		return $this->roleRepository->create($roleName);
	}

	/**
	 * @param $permissionName
	 * @return \Artesaos\Guardian\Permission
	 */
	public function createPermission($permissionName)
	{
		return $this->permissionRepository->create($permissionName);
	}

}