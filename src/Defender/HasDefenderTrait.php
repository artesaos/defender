<?php

namespace Artesaos\Defender;

use Artesaos\Defender\Traits\HasUserPermissionsTrait;
use Artesaos\Defender\Traits\HasUserRolesTrait;

/**
 * Class HasDefenderTrait.
 */
trait HasDefenderTrait
{
    use HasUserRolesTrait, HasUserPermissionsTrait;
}
