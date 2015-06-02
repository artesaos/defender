<?php

namespace Artesaos\Defender\Repositories\Eloquent;

use Artesaos\Defender\Contracts\Repositories\AbstractRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;

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
     * @var Model
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
     * @param $id
     *
     * @return \Illuminate\Support\Collection|null|static
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function findByName($name)
    {
        return $this->model->where('name', '=', $name)->first();
    }

    /**
     * @param $value
     * @param $key
     */
    public function getList($value, $key = 'id')
    {
        return $this->model->lists($value, $key);
    }
}
