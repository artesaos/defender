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
		Schema::create(Config::get('guardian.permission_role_table'), function(Blueprint $table)
        {
            $table->integer(Config::get('guardian.permission_key'))->unsigned()->index();
            $table->foreign(Config::get('guardian.permission_key'))->references('id')
                  ->on(Config::get('guardian.permission_table'))
                  ->onDelete('cascade');

            $table->integer(Config::get('guardian.role_key'))->unsigned()->index();
            $table->foreign(Config::get('guardian.role_key'))->references('id')
                  ->on(Config::get('guardian.role_table'))
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
        Schema::table(Config::get('guardian.permission_role_table'), function(Blueprint $table)
        {
            $table->dropForeign(Config::get('guardian.permission_role_table').'_'.Config::get('guardian.permission_key').'_foreign');
            $table->dropForeign(Config::get('guardian.permission_role_table').'_'.Config::get('guardian.role_key').'_foreign');
        });

		Schema::drop(Config::get('guardian.permission_role_table'));
	}

}
