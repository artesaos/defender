<?php  namespace Artesaos\Guardian;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PermissionRolePivot
 * @package Artesaos\Guardian
 */
class PermissionRolePivot extends Pivot {

	/**
	 * @var array
	 */
	protected $casts = [
		'value' => 'boolean'
	];

}