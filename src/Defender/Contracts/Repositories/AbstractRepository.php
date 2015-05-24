<?php

namespace Artesaos\Defender\Contracts\Repositories;

interface AbstractRepository
{
    public function findById($id);

    public function findByName($name);

    public function getList($value, $key = 'id');
}
