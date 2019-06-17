<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDelvaryTimesTable extends Migration {

	public function up()
	{
		Schema::create('delvary_times', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('duration', 255);
			$table->time('from');
			$table->time('to');
		});
	}

	public function down()
	{
		Schema::drop('delvary_times');
	}
}