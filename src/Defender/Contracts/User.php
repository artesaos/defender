<?php

namespace Artesaos\Defender\Contracts;

/**
 * Interface User.
 */
interface User
{
    /**
     * Find a user by its id.
     *
     * @param int $id
     *
     * @return \Artesaos\Defender\Contracts\User
     */
    public function findById($id);

    /**
     * Attach the given role.
     *
     * @param \Artesaos\Defender\Role $role
     */
    public function attachRole($role);

    /**
     * Attach the given permission.
     *
     * @param array|\Artesaos\Defender\Permission $permission
     * @param array                               $options
     */
    public function attachPermission($permission, array $options);
}
