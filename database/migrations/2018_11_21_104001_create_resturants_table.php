<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResturantsTable extends Migration {

	public function up()
	{
		Schema::create('resturants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->boolean('status')->default(1);
			$table->double('minimum', 8,2)->default('0.0');
			$table->double('delivery_cost', 8,2);
			$table->enum('delivery_way', array('delegate', 'takeaway'));
			$table->integer('rate');
			$table->string('city', 255);
			$table->string('district', 255);
			$table->timeTz('order_time_from');
			$table->timeTz('order_time_to');
			$table->date('order_date_from');
			$table->date('order_date_to');
			$table->double('longitude', 10,10);
			$table->double('latitude', 10,10);
			$table->string('api_token', 255);
			$table->string('password', 255);
			$table->string('email', 255);
			$table->string('phone', 255);
			$table->string('whatsapp', 255);
			$table->string('photo', 255);

		});
	}

	public function down()
	{
		Schema::drop('resturants');
	}
}