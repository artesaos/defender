<?php

namespace Artesaos\Defender\Contracts;

use Artesaos\Defender\Pivots\PermissionUserPivot;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface Permission.
 */
interface Permission
{
    /**
     * Many-to-many permission-role relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * Many-to-many permission-user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();

    /**
     * @param Model  $parent
     * @param array  $attributes
     * @param string $table
     * @param bool   $exists
     * @param  string|null  $using
     *
     * @return PermissionUserPivot|\Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null);
}
