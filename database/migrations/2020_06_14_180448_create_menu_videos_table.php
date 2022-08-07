<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuVideosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('menu_videos') ) {
			Schema::create('menu_videos', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('menu_id')->unsigned()->index('menu_videos_menu_id_foreign');
				$table->integer('movie_id')->unsigned()->nullable()->index('menu_videos_movie_id_foreign');
				$table->integer('tv_series_id')->unsigned()->nullable()->index('menu_videos_tv_series_id_foreign');
				$table->integer('live_event_id')->nullable();
				$table->integer('audio_id')->nullable();
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
		Schema::drop('menu_videos');
	}

}
