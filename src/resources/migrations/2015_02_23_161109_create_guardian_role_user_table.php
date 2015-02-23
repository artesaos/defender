<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuardianRoleUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('guardian.role_user_table', 'role_user'), function (Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on(config('auth.table', 'users'))->onDelete('cascade');

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
		Schema::table(config('guardian.role_user_table', 'role_user'), function (Blueprint $table)
		{
			$table->dropForeign(config('guardian.role_user_table', 'role_user').'_user_id_foreign');
			$table->dropForeign(config('guardian.role_user_table', 'role_user').'_'.config('guardian.role_key', 'role_id') . '_foreign');
		});

		Schema::drop(config('guardian.role_user_table', 'role_user'));
	}

}
