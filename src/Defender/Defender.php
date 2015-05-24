<?php

namespace Artesaos\Defender;

use Artesaos\Defender\Contracts\Defender as DefenderContract;
use Artesaos\Defender\Contracts\Repositories\PermissionRepository;
use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Illuminate\Contracts\Foundation\Application;

/**
 *
 */
class Defender implements DefenderContract
{
    /**
     * The Laravel Application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The RoleRepository implementation.
     *
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * The PermissionRepository implementation.
     *
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * Class constructor.
     *
     * @param Application          $app                  Laravel Application
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(Application $app, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->app = $app;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get the current authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return $this->app['auth']->user();
    }

    /**
     * Check if the authenticated user has the given permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can($permission)
    {
        if (!is_null($this->getUser())) {
            return $this->getUser()->can($permission);
        }

        return false;
    }

    /**
     * Check if the authenticated user has the given permission
     * using only the roles.
     *
     * @param $permission
     *
     * @return bool
     */
    public function canWithRolePermissions($permission)
    {
        if (!is_null($this->getUser())) {
            return $this->getUser()->canWithRolePermissions($permission);
        }

        return false;
    }

    /**
     * Return if the authenticated user has the given role.
     *
     * @param $roleName
     *
     * @return bool
     */
    public function hasRole($roleName)
    {
        if (!is_null($this->getUser())) {
            return $this->getUser()->hasRole($roleName);
        }

        return false;
    }

    /**
     * Return if the authenticated user has the given role.
     *
     * @param $roleName
     *
     * @return bool
     */
    public function is($roleName)
    {
        return $this->hasRole($roleName);
    }

    /**
     * Check if a role with the given name exists.
     *
     * @param $roleName
     *
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
     *
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
     *
     * @return \Artesaos\Defender\Role|null
     */
    public function findRole($roleName)
    {
        return $this->roleRepository->findByName($roleName);
    }

    /**
     * * Find a role by its id.
     *
     * @param $roleId
     *
     * @return mixed
     */
    public function findRoleById($roleId)
    {
        return $this->roleRepository->findById($roleId);
    }

    /**
     * Get the permission with the given name.
     *
     * @param $permissionName
     *
     * @return \Artesaos\Defender\Permission|null
     */
    public function findPermission($permissionName)
    {
        return $this->permissionRepository->findByName($permissionName);
    }

    /**
     * Find a permission by its id.
     *
     * @param $permissionId
     *
     * @return mixed
     */
    public function findPermissionById($permissionId)
    {
        return $this->permissionRepository->findById($permissionId);
    }

    /**
     * Returns a list of existing permissions.
     *
     * @return mixed
     */
    public function permissionsList()
    {
        return $this->permissionRepository->getList('name', 'id');
    }

    /**
     * Returns a list of existing roles.
     *
     * @return mixed
     */
    public function rolesList()
    {
        return $this->roleRepository->getList('name', 'id');
    }

    /**
     * Create a new role.
     * Uses a repository to actually create the role.
     *
     * @param $roleName
     *
     * @return \Artesaos\Defender\Role
     */
    public function createRole($roleName)
    {
        return $this->roleRepository->create($roleName);
    }

    /**
     * @param $permissionName
     * @param null $readableName
     *
     * @return Permission
     */
    public function createPermission($permissionName, $readableName = null)
    {
        return $this->permissionRepository->create($permissionName, $readableName);
    }
}
