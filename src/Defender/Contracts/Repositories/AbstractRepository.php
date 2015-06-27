<?php

namespace Artesaos\Defender\Contracts\Repositories;

interface AbstractRepository
{
    public function all();

    public function paginate($perPage = 10);

    public function findById($id);

    public function findByName($name);

    public function getList($value, $key = 'id');

    public function make(array $with = []);
}
