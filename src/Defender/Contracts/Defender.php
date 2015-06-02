<?php

namespace Artesaos\Defender\Contracts;

interface Defender
{
    /**
     * Get the current authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser();

    /**
     * Check if the authenticated user has the given permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can($permission);

    /**
     * Check if the authenticated user has the given permission
     * using only the roles.
     *
     * @param $permission
     *
     * @return bool
     */
    public function canWithRolePermissions($permission);

    /**
     * Return if the authenticated user has the given role.
     *
     * @param $roleName
     *
     * @return bool
     */
    public function hasRole($roleName);

    /**
     * Return if the authenticated user has the given role.
     *
     * @param $roleName
     *
     * @return bool
     */
    public function is($roleName);

    /**
     * Check if a role with the given name exists.
     *
     * @param $roleName
     *
     * @return bool
     */
    public function roleExists($roleName);

    /**
     * Check if a permission with the given name exists.
     *
     * @param $permissionName
     *
     * @return bool
     */
    public function permissionExists($permissionName);

    /**
     * Get the role with the given name.
     *
     * @param $roleName
     *
     * @return \Artesaos\Defender\Role|null
     */
    public function findRole($roleName);

    /**
     * * Find a role by its id.
     *
     * @param $roleId
     *
     * @return mixed
     */
    public function findRoleById($roleId);

    /**
     * Get the permission with the given name.
     *
     * @param $permissionName
     *
     * @return \Artesaos\Defender\Permission|null
     */
    public function findPermission($permissionName);

    /**
     * Find a permission by its id.
     *
     * @param $permissionId
     *
     * @return mixed
     */
    public function findPermissionById($permissionId);

    /**
     * @return mixed
     */
    public function permissionsList();

    /**
     * @return mixed
     */
    public function rolesList();

    /**
     * Create a new role.
     * Uses a repository to actually create the role.
     *
     * @param $roleName
     *
     * @return \Artesaos\Defender\Role
     */
    public function createRole($roleName);

    /**
     * @param $permissionName
     * @param $readableName
     *
     * @return \Artesaos\Defender\Permission
     */
    public function createPermission($permissionName, $readableName = null);
}
