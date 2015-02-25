<?php  namespace Artesaos\Guardian;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionUserPivot extends Pivot {

	protected $casts = [
		'value' => 'integer'
	];

}