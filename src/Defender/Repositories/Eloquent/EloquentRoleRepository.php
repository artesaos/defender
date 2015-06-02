<?php

namespace Artesaos\Defender\Repositories\Eloquent;

use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Artesaos\Defender\Exceptions\RoleExistsException;
use Artesaos\Defender\Role;
use Illuminate\Contracts\Foundation\Application;

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
     * @param array $extraColumns
     *
     * @throws \Exception
     *
     * @return static
     */
    public function create($roleName, $extraColumns = [])
    {
        if (!is_null($this->findByName($roleName))) {
            // TODO: add translation support
            throw new RoleExistsException('A role with the given name already exists');
        }

        $columns = [
            'name' => $roleName
        ];

        // Extras columns?
        if (!empty($extraColumns)){
            $columns = array_merge($columns, $extraColumns);
        }

        return $role = $this->model->create($columns);
    }
}
