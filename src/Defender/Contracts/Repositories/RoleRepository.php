<?php

namespace Artesaos\Defender\Contracts\Repositories;

interface RoleRepository extends AbstractRepository
{
    public function create($roleName);
}
