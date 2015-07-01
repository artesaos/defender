<?php

namespace Artesaos\Defender\Contracts\Repositories;

interface RoleRepository extends AbstractRepository
{
    /**
     * Create a new role with the given name.
     *
     * @param $roleName
     *
     * @throws \Exception
     *
     * @return \Artesaos\Defender\Role
     */
    public function create($roleName);
}
