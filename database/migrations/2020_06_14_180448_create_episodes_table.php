<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEpisodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('episodes') ) {
			Schema::create('episodes', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('seasons_id')->unsigned()->index('episodes_seasons_id_foreign');
				$table->string('tmdb_id', 191)->nullable();
				$table->string('thumbnail', 200)->nullable();
				$table->integer('episode_no')->nullable();
				$table->string('title', 191);
				$table->char('tmdb', 191)->nullable();
				$table->string('duration', 191)->nullable();
				$table->text('detail', 65535)->nullable();
				$table->string('a_language', 191)->nullable();
				$table->boolean('subtitle')->nullable();
				$table->string('released', 191)->nullable();
				$table->char('type', 191)->default('E');
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
		Schema::drop('episodes');
	}

}
