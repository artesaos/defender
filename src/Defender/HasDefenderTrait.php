<?php

namespace Artesaos\Defender;

use Artesaos\Defender\Traits\HasDefenderTrait as DefenderTrait;

/**
 * @deprecated Use \Artesaos\Defender\Traits\HasDefenderTrait instead.
 */
trait HasDefenderTrait
{
    use DefenderTrait; // Semver fix
}
