<?php

namespace Artesaos\Defender;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission.
 */
class Permission extends Model implements Contracts\Permission
{
    use Traits\Models\Permission;
}
