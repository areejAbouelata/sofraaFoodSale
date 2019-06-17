<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('client_id')->unsigned();
			$table->integer('resturant_id')->unsigned();
			$table->double('total', 8,2);
			$table->double('delivary_cost', 8,2);
			$table->enum('status', array('binding', 'accepted', 'rejected', 'delivered'));
			$table->enum('payment_type', array('cache', 'creadit'));
			$table->string('delivary_address', 255);
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}