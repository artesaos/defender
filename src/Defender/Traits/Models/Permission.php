<?php

namespace Artesaos\Defender\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Artesaos\Defender\Pivots\PermissionRolePivot;
use Artesaos\Defender\Pivots\PermissionUserPivot;

/**
 * Trait Permission.
 */
trait Permission
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // Must to be declared before parent::__construct call
        $this->fillable = $fillable = [
            'name',
            'readable_name',
        ];

        parent::__construct($attributes);

        $this->table = config('defender.permission_table', 'permissions');
    }

    /**
     * Many-to-many permission-role relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('defender.role_model'),
            config('defender.permission_role_table'),
            config('defender.permission_key'),
            config('defender.role_key')
        )->withPivot('value', 'expires');
    }

    /**
     * Many-to-many permission-user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            config('defender.user_model'),
            config('defender.permission_user_table'),
            config('defender.permission_key'),
            'user_id'
        )->withPivot('value', 'expires');
    }

    /**
     * @param Model  $parent
     * @param array  $attributes
     * @param string $table
     * @param bool   $exists
     * @param  string|null  $using
     *
     * @return PermissionUserPivot|\Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        $userModel = app()['config']->get('defender.user_model');
        $roleModel = app()['config']->get('defender.role_model');

        if ($parent instanceof $userModel) {
            return new PermissionUserPivot($parent, $attributes, $table, $exists, $using);
        }

        if ($parent instanceof $roleModel) {
            return new PermissionRolePivot($parent, $attributes, $table, $exists, $using);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
