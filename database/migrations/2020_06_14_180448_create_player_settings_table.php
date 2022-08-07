<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayerSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('player_settings') ) {
			Schema::create('player_settings', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('logo', 191)->nullable();
				$table->boolean('logo_enable')->nullable();
				$table->string('cpy_text', 191)->nullable();
				$table->boolean('share_opt')->nullable();
				$table->boolean('auto_play')->nullable();
				$table->boolean('speed')->nullable();
				$table->boolean('thumbnail')->nullable();
				$table->boolean('info_window')->nullable();
				$table->string('skin', 100)->nullable();
				$table->boolean('loop_video')->nullable();
				$table->boolean('is_resume')->nullable()->default(0);
				$table->string('player_google_analytics_id', 199)->nullable();
				$table->integer('subtitle_font_size')->nullable();
				$table->string('subtitle_color', 191)->nullable();
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
		Schema::drop('player_settings');
	}

}
