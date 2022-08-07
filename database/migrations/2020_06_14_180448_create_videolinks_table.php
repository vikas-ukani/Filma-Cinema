<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideolinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('videolinks') ) {
			Schema::create('videolinks', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('movie_id')->unsigned()->nullable()->index('videolinks_movie_id_foreign');
				$table->integer('episode_id')->unsigned()->nullable()->index('videolinks_episode_id_foreign');
				$table->string('type', 200)->nullable();
				$table->text('iframeurl')->nullable();
				$table->string('ready_url', 191)->nullable();
				$table->string('url_360', 191)->nullable();
				$table->string('url_480', 191)->nullable();
				$table->string('url_720', 191)->nullable();
				$table->string('url_1080', 191)->nullable();
				$table->timestamps();
			});
		}
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('videolinks');
	}

}
