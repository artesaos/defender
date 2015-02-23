<?php namespace Artisans\Guardian\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractEloquentRepository {

	protected $app;

	protected $model;

	public function __construct(Model $model)
	{
		$this->app   = app();
		$this->model = $model;
	}

	public function findById($id)
	{
		return $this->model->find($id);
	}

}