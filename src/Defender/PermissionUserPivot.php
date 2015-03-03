<?php  namespace Artesaos\Defender;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PermissionUserPivot
 * @package Artesaos\Defender
 */
class PermissionUserPivot extends Pivot {

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