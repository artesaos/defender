<?php namespace Artisans\Guardian\Repositories\Eloquent;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractEloquentRepository
 *
 * @package Artisans\Guardian\Repositories\Eloquent
 */
abstract class AbstractEloquentRepository {

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
		$this->app   = $app;
		$this->model = $model;
	}

    /**
     * @param $id
     * @return \Illuminate\Support\Collection|null|static
     */
    public function findById($id)
	{
		return $this->model->find($id);
	}

}