<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuardianPermissionUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('guardian.permission_user_table', 'permission_user'), function (Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on(config('auth.table', 'users'))->onDelete('cascade');

			$table->integer(config('guardian.permission_key', 'permission_id'))->unsigned()->index();
			$table->foreign(config('guardian.permission_key', 'permission_id'))->references('id')
				  ->on(config('guardian.permission_table', 'permissions'))
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
		Schema::table(config('guardian.permission_user_table', 'permission_user'), function (Blueprint $table)
		{
			$table->dropForeign(config('guardian.permission_user_table', 'permission_user').'_user_id_foreign');
			$table->dropForeign(config('guardian.permission_user_table', 'permission_user').'_'.config('guardian.permission_key', 'permission_id').'_foreign');
		});

		Schema::drop(config('guardian.permission_user_table', 'permission_user'));
	}

}
