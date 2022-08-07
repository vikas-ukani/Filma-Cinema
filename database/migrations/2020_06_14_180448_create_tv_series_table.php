<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTvSeriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('tv_series') ) {
			Schema::create('tv_series', function(Blueprint $table)
			{
				$table->increments('id');
				$table->text('keyword')->nullable();
				$table->text('description')->nullable();
				$table->string('title', 191);
				$table->string('tmdb_id', 191)->nullable();
				$table->char('tmdb', 191)->nullable();
				$table->string('fetch_by', 100)->nullable();
				$table->string('thumbnail', 191)->nullable();
				$table->string('poster', 191)->nullable();
				$table->string('genre_id', 191)->nullable();
				$table->text('detail', 65535)->nullable();
				$table->float('rating')->nullable();
				$table->float('episode_runtime')->nullable();
				$table->string('maturity_rating', 191)->nullable();
				$table->boolean('featured')->default(0);
				$table->char('type', 191)->default('T');
				$table->integer('status')->unsigned()->default(1);
				$table->integer('created_by')->unsigned();
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
		Schema::drop('tv_series');
	}

}
