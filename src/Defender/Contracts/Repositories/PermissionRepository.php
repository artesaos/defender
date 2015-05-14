<?php

namespace Artesaos\Defender\Contracts\Repositories;

interface PermissionRepository extends AbstractRepository
{
    public function create($permissionName, $readableName = null);
}
