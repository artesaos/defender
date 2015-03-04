<?php namespace Artesaos\Defender;

use Artesaos\Defender\Traits\HasRolesTrait;
use Artesaos\Defender\Traits\HasPermissionsTrait;

/**
 * Class HasDefenderTrait
 *
 * @package Artesaos\Defender
 */
trait HasDefenderTrait {

	use HasRolesTrait, HasPermissionsTrait;

}