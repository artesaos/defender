<?php

namespace Artesaos\Defender\Contracts\Permissions\Resources;

use IteratorAggregate;
use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface ResourceCollection.
 *
 * @see \Illuminate\Support\Collection
 */
interface Collection extends IteratorAggregate, Arrayable, Jsonable, JsonSerializable
{
    /**
     * @param Resource|string $resource
     *
     * @return Collection
     */
    public function add($resource);

    /**
     * @param string $name
     *
     * @return Resource
     */
    public function get($name);

    /**
     * Ger new collection by resources name.
     *
     * @param array $names
     *
     * @return Collection
     */
    public function byResources(array $names);
}