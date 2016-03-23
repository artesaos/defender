<?php

namespace Artesaos\Defender\Permissions\Resources;


use Artesaos\Defender\Contracts\Permissions\Resources\Collection as CollectionContract;
use Artesaos\Defender\Contracts\Permissions\Resources\Resource as ResourceContract;
use Illuminate\Support\Collection as IlluminateCollection;

/**
 * Class ResourceCollection.
 *
 * @see Collection
 */
class Collection implements CollectionContract
{
    /**
     * @var IlluminateCollection
     */
    protected $resources;

    public function __construct()
    {
        $this->resources = new IlluminateCollection();
    }

    /**
     * @param ResourceContract|string $resource
     *
     * @return CollectionContract
     */
    public function add($resource)
    {
        if (is_string($resource)) {
            $resource = app($resource);
        }

        if (! $resource instanceof ResourceContract) {
            throw new \InvalidArgumentException('$resource not is a ResourceContract instance', 501);
        }

        $this->resources->put($resource->getName(), $resource);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return ResourceContract
     */
    public function get($name)
    {
        return $this->resources->get($name);
    }

    /**
     * Ger new collection by resources name.
     *
     * @param array $names
     *
     * @return CollectionContract
     */
    public function byResources(array $names)
    {
        $new = new static();

        $new->resources = $this->resources->filter(function (ResourceContract $resource) use ($names) {
            return in_array($resource->getName(), $names);
        });

        return $new;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->resources->getIterator();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->resources->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return $this->resources->toJson($options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->resources->jsonSerialize();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return $this->toArray();
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, array $arguments = [])
    {
        return call_user_func_array([$this->resources, $name], $arguments);
    }
}