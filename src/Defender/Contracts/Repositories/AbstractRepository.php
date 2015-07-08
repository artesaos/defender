<?php

namespace Artesaos\Defender\Contracts\Repositories;

/**
 * Interface AbstractRepository.
 */
interface AbstractRepository
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param int $perPage
     *
     * @return mixed
     */
    public function paginate($perPage = 10);

    /**
     * @param array $with
     *
     * @return mixed
     */
    public function make(array $with = []);

    /**
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findById($id);

    /**
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByName($name);

    /**
     * @param string|int $value
     * @param string     $key
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList($value, $key = 'id');
}
