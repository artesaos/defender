<?php

namespace Artesaos\Defender\Permissions\Resources;

use ArrayIterator;
use Artesaos\Defender\Contracts\Permissions\Resources\Resource as ResourceContract;

abstract class Resource implements ResourceContract
{
    /**
     * Permissions.
     *
     * @var array
     */
    protected $permissions = [];

    /**
     * Resource name.
     *
     * @var string
     */
    protected $name;

    /**
     * Resource description.
     *
     * @var string
     */
    protected $description;

    /**
     * Resource label.
     *
     * @var string
     */
    protected $label;

    /**
     * Returns an array of permissions.
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Returns the resource name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return array_combine($this->permissions, $this->permissions);
    }

    /**
     * Takes the permissions with resource name prefixed.
     *
     * @return array
     */
    public function getNamedPermissions()
    {
        $permissions = [];

        foreach ($this->permissions as $name) {
            $permissions[] = $this->name . '::' . $name;
        }

        return $permissions;
    }

    /**
     * Returns the resource description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the resource label.
     *
     * @return string
     */
    public function getLabel()
    {
        if (empty($this->label)) {
            return ucfirst($this->getName());
        }

        return $this->label;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
            'permissions' => $this->getPermissions(),
            'trans' => [
                'permissions' => $this->getTranslatedPermissions(),
                'label' => $this->getTranslatedPermissions(),
            ],
        ];
    }

    /**
     * Returns the label translated
     *
     * @return string
     */
    public function getTranslatedLabel()
    {
        $label = $this->label;
        if (empty($label)) {
            $label = $this->getName();
        }

        return trans('acl::labels.' . $label);
    }

    /**
     * Returns an array with the translated permissions.
     * @return array
     */
    public function getTranslatedPermissions()
    {
        $permissions = [];

        foreach ($this->permissions as $permission) {
            $permissions[$permission] = trans('acl::permissions.' . $permission);
        }

        return $permissions;
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
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
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
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
}