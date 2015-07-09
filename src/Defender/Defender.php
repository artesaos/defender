<?php

namespace Artesaos\Defender;

use Artesaos\Defender\Contracts\Defender as DefenderContract;
use Artesaos\Defender\Contracts\Repositories\PermissionRepository;
use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class Defender.
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
     * @param Application          $app
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
        return $this->app['defender.auth']->user();
    }

    /**
     * Check if the authenticated user has the given permission.
     *
     * @param string $permission
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
     * @param string $permission
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
     * @param string $roleName
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
     * Return if the authenticated user has any of the given roles.
     *
     * @param string $roles
     *
     * @return bool
     */
    public function hasRoles($roles)
    {
        if (!is_null($this->getUser())) {
            return $this->getUser()->hasRoles($roles);
        }

        return false;
    }

    /**
     * Return if the authenticated user has the given role.
     *
     * @param string $roleName
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
     * @param string $roleName
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
     * @param string $permissionName
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
     * @param string $roleName
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
     * @param int $roleId
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
     * @param string $permissionName
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
     * @param int $permissionId
     *
     * @return \Artesaos\Defender\Permission|null
     */
    public function findPermissionById($permissionId)
    {
        return $this->permissionRepository->findById($permissionId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissionsList()
    {
        return $this->permissionRepository->getList('name', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function rolesList()
    {
        return $this->roleRepository->getList('name', 'id');
    }

    /**
     * Create a new role.
     * Uses a repository to actually create the role.
     *
     * @param string $roleName
     *
     * @return \Artesaos\Defender\Role
     */
    public function createRole($roleName)
    {
        return $this->roleRepository->create($roleName);
    }

    /**
     * @param string $permissionName
     * @param string $readableName
     *
     * @return Permission
     */
    public function createPermission($permissionName, $readableName = null)
    {
        return $this->permissionRepository->create($permissionName, $readableName);
    }

    /**
     * @return Javascript
     */
    public function javascript()
    {
        return $this->app['defender.javascript'];
    }
}
