<?php  namespace Artesaos\Defender;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PermissionRolePivot
 * @package Artesaos\Defender
 */
class PermissionRolePivot extends Pivot {

	/**
	 * @var array
	 */
	protected $casts = [
		'value' => 'boolean'
	];

	/**
	 * @var array
	 */
	protected $dates = [
		'expires'
	];

}