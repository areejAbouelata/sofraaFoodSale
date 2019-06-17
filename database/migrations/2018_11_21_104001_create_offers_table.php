<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration {

	public function up()
	{
		Schema::create('offers', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 255);
			$table->integer('resturant_id')->unsigned();
			$table->text('hint');
			$table->string('photo') ;
			$table->date('date_from');
			$table->date('date_to');
		});
	}

	public function down()
	{
		Schema::drop('offers');
	}
}