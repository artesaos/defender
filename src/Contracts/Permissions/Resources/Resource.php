<?php

namespace Artesaos\Defender\Contracts\Permissions\Resources;

use IteratorAggregate;
use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface Resource extends IteratorAggregate, Jsonable, Arrayable, JsonSerializable
{
    /**
     * Returns an array of permissions.
     *
     * @return array
     */
    public function getName();

    /**
     * Returns the resource name.
     *
     * @return string
     */
    public function getPermissions();

    /**
     * Takes the permissions with resource name prefixed.
     *
     * @return array
     */
    public function getNamedPermissions();

    /**
     * @return array
     */
    public function getList();

    /**
     * Returns the resource description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Returns the resource label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns an array with the translated permissions.
     * @return array
     */
    public function getTranslatedPermissions();

    /**
     * Returns the label translated
     *
     * @return string
     */
    public function getTranslatedLabel();
}