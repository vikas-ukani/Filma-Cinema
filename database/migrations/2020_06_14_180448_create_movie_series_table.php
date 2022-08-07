<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovieSeriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('movie_series') ) {
			Schema::create('movie_series', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('movie_id')->unsigned()->index('movie_series_movie_id_foreign');
				$table->string('series_movie_id', 191);
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
		Schema::drop('movie_series');
	}

}
