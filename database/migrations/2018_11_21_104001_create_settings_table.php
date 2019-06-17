<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->double('percentage', 8,2);
			$table->text('bank_acount');
			$table->string('site_name', 255);
			$table->longText('about_us');
			$table->string('facebook_url', 255);
			$table->string('tweeter_url', 255);
			$table->string('insta_url', 255);
			$table->text('slogan');
			$table->string('logo', 255);
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}