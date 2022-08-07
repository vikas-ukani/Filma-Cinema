<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeasonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('seasons') ) {
			Schema::create('seasons', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('tv_series_id')->unsigned()->index('seasons_tv_series_id_foreign');
				$table->string('tmdb_id', 191)->nullable();
				$table->bigInteger('season_no');
				$table->string('season_slug', 191)->nullable();
				$table->char('tmdb', 191)->nullable();
				$table->string('publish_year', 191)->nullable();
				$table->string('thumbnail', 191)->nullable();
				$table->string('poster', 191)->nullable();
				$table->string('actor_id', 191)->nullable();
				$table->string('a_language', 191)->nullable();
				$table->text('detail', 65535)->nullable();
				$table->boolean('featured')->default(0);
				$table->char('type', 191)->default('S');
				$table->integer('is_protect')->default(0);
				$table->string('password',191)->nullable();
				$table->longtext('trailer_url',65535)->nullable();

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
		Schema::drop('seasons');
	}

}
