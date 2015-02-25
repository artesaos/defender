<?php  namespace Artesaos\Guardian\Contracts\Repositories;


interface AbstractRepository {

	public function findById($id);

	public function findByName($name);

	public function getList($value, $key);

}