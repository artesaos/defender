<?php

namespace Artesaos\Defender\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Foundation\Application;
use Artesaos\Defender\Contracts\Repositories\AbstractRepository;

/**
 * Class AbstractEloquentRepository.
 */
abstract class AbstractEloquentRepository implements AbstractRepository
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model|\Illuminate\Database\Eloquent\Builder
     */
    protected $model;

    /**
     * @param Application $app
     * @param Model       $model
     */
    public function __construct(Application $app, Model $model)
    {
        $this->app = $app;
        $this->model = $model;
    }

    /**
     * Returns all from the current model.
     *
     * @return static
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Return paginated results.
     *
     * @param int $perPage Number of results per page
     *
     * @return static
     */
    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Return a new instance of the current model.
     *
     * @param array $attributes
     *
     * @return static
     */
    public function newInstance(array $attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByName($name)
    {
        return $this->model->where('name', '=', $name)->first();
    }

    /**
     * @param string|int $value
     * @param string     $key
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList($value, $key = 'id')
    {
        return $this->model->pluck($value, $key);
    }

    /**
     * Set Relationships.
     *
     * @param array $with Relationships
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function make(array $with = [])
    {
        return $this->model->with($with);
    }
}
