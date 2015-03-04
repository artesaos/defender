<?php namespace Artesaos\Defender;

use Artesaos\Defender\Traits\HasUserRolesTrait;
use Artesaos\Defender\Traits\HasUserPermissionsTrait;

/**
 * Class HasDefenderTrait
 *
 * @package Artesaos\Defender
 */
trait HasDefenderTrait {

	use HasUserRolesTrait, HasUserPermissionsTrait;

}