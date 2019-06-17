<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderProductsTable extends Migration {

	public function up()
	{
		Schema::create('order_products', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('amount');
			$table->double('product_price', 8,2);
			$table->integer('product_id');
			$table->integer('order_id');
			$table->text('note');
		});
	}

	public function down()
	{
		Schema::drop('order_products');
	}
}