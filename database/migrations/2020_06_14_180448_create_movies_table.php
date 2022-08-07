<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMoviesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('movies') ) {
			Schema::create('movies', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('tmdb_id')->nullable();
				$table->string('title', 191);
				$table->string('slug', 191)->nullable();
				$table->text('keyword')->nullable();
				$table->text('description')->nullable();
				$table->string('duration', 191)->nullable();
				$table->string('thumbnail', 191)->nullable();
				$table->string('poster', 191)->nullable();
				$table->char('tmdb', 191)->nullable();
				$table->string('fetch_by', 100)->nullable();
				$table->string('director_id', 191)->nullable();
				$table->string('actor_id', 191)->nullable();
				$table->string('genre_id', 191)->nullable();
				$table->string('trailer_url', 191)->nullable();
				$table->text('detail', 65535)->nullable();
				$table->float('rating')->nullable();
				$table->string('maturity_rating', 191)->nullable();
				$table->boolean('subtitle')->nullable();
				$table->integer('publish_year')->nullable();
				$table->string('released', 191)->nullable();
				$table->string('upload_video', 100)->nullable();
				$table->boolean('featured')->nullable();
				$table->boolean('series')->nullable();
				$table->string('a_language', 191)->nullable();
				$table->string('audio_files', 191)->nullable();
				$table->char('type', 191)->default('M');
				$table->boolean('live')->nullable()->default(0);
				$table->boolean('livetvicon')->nullable();
				$table->integer('status')->default(1);
				$table->integer('is_protect')->default(0);
				$table->string('password',191)->nullable();
				$table->integer('created_by')->unsigned()->default(1);
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
		Schema::drop('movies');
	}

}
