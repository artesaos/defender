<?php

namespace Artesaos\Defender\Repositories\Eloquent;

use Artesaos\Defender\Contracts\Role;
use Illuminate\Contracts\Foundation\Application;
use Artesaos\Defender\Exceptions\RoleExistsException;
use Artesaos\Defender\Contracts\Repositories\RoleRepository;

/**
 * Class EloquentRoleRepository.
 */
class EloquentRoleRepository extends AbstractEloquentRepository implements RoleRepository
{
    /**
     * @param Application $app
     * @param Role        $model
     */
    public function __construct(Application $app, Role $model)
    {
        parent::__construct($app, $model);
    }

    /**
     * Create a new role with the given name.
     *
     * @param $roleName
     *
     * @throws \Exception
     *
     * @return Role
     */
    public function create($roleName)
    {
        if (! is_null($this->findByName($roleName))) {
            // TODO: add translation support
            throw new RoleExistsException('A role with the given name already exists');
        }

        return $role = $this->model->create(['name' => $roleName]);
    }
}
