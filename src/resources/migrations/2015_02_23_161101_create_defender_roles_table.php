<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefenderRolesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('defender.role_table', 'roles'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop(config('defender.role_table', 'roles'));
    }
}
