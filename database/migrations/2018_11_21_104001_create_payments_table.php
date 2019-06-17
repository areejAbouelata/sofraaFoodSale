<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	public function up()
	{
		Schema::create('payments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->double('minimum');
			$table->double('payed', 8,2);
			$table->double('rest', 8,2);
			$table->integer('resturant_id');
		});
	}

	public function down()
	{
		Schema::drop('payments');
	}
}