<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefenderPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('defender.permission_table', 'permissions'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('readable_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop(config('defender.permission_table', 'permissions'));
    }
}
