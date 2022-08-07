<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovieCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('movie_comments') ) {
			Schema::create('movie_comments', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->string('name', 200)->nullable();
				$table->integer('user_id');
				$table->string('email', 100)->nullable();
				$table->integer('movie_id')->nullable();
				$table->integer('tv_series_id')->nullable();
				$table->text('comment')->nullable();
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
		Schema::drop('movie_comments');
	}

}
