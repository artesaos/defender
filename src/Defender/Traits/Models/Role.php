<?php

namespace Artesaos\Defender\Traits\Models;

use Artesaos\Defender\Traits\Permissions\RoleHasPermissions;

/**
 * Trait Role.
 */
trait Role
{
    use RoleHasPermissions;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = [
                'name',
            ];

        parent::__construct($attributes);

        $this->table = config('defender.role_table', 'roles');
    }

    /**
     * Many-to-many role-user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            config('defender.user_model'),
            config('defender.role_user_table'),
            config('defender.role_key'),
            'user_id'
        );
    }
}
