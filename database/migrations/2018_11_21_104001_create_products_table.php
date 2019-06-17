<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->text('discribtion');
			$table->time('duration');
			$table->double('price', 8,2);
			$table->string('photo', 255);
			$table->integer('resturant_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}