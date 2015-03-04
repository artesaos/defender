<?php  namespace Artesaos\Defender\Pivots;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

abstract class AbstractDefenderPivot extends Pivot {

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