<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration {

	public function up()
	{
		Schema::create('contacts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('client_id')->unsigned()->nullable();
			$table->string('name', 255);
			$table->string('email', 255)->unique();
			$table->string('phone', 255);
			$table->enum('type', array('complaint', 'suggetion', 'query'));
			$table->text('discribtion');
		});
	}

	public function down()
	{
		Schema::drop('contacts');
	}
}