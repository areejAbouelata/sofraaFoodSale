<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('email');
			$table->string('phone', 255);
			$table->string('city', 255);
			$table->string('district', 255);
			$table->string('home_discribtion', 255);
			$table->string('password', 255);
			$table->string('api_token', 255);
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}