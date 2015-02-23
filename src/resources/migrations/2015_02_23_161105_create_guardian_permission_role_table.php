<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuardianPermissionRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('guardian.permission_role_table', 'permission_role'), function (Blueprint $table)
		{
			$table->integer(config('guardian.permission_key', 'permission_id'))->unsigned()->index();
			$table->foreign(config('guardian.permission_key', 'permission_id'))->references('id')
				  ->on(config('guardian.permission_table', 'permissions'))
				  ->onDelete('cascade');

			$table->integer(config('guardian.role_key', 'role_id'))->unsigned()->index();
			$table->foreign(config('guardian.role_key', 'role_id'))->references('id')
				  ->on(config('guardian.role_table', 'roles'))
				  ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(config('guardian.permission_role_table', 'permission_role'), function (Blueprint $table)
		{
			$table->dropForeign(config('guardian.permission_role_table', 'permission_role').'_'.config('guardian.permission_key', 'permission_id') . '_foreign');
			$table->dropForeign(config('guardian.permission_role_table', 'permission_role').'_'.config('guardian.role_key', 'role_id') . '_foreign');
		});

		Schema::drop(config('guardian.permission_role_table', 'permission_role'));
	}

}
