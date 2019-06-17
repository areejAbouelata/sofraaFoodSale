<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 255);
			$table->datetime('notification_date');
			$table->string('action', 255);
			$table->text('content');
			$table->integer('action_id');
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}