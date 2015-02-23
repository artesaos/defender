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
		Schema::create(Config::get('guardian.permission_user_table'), function(Blueprint $table)
        {
            $table->integer(Config::get('guardian.permission_key'))->unsigned()->index();
            $table->foreign(Config::get('guardian.permission_key'))->references('id')
                  ->on(Config::get('guardian.permission_table'))
                  ->onDelete('cascade');

            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on(Config::get('auth.model'))->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table(Config::get('guardian.permission_user_table'), function(Blueprint $table)
        {
            $table->dropForeign(Config::get('guardian.permission_user_table').'_'.Config::get('guardian.permission_key').'_foreign');
            $table->dropForeign(Config::get('guardian.permission_user_table').'_user_id_foreign');
        });

		Schema::drop(Config::get('guardian.permission_user_table'));
	}

}
