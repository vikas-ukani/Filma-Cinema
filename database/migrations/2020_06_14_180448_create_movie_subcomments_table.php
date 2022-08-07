<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovieSubcommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('movie_subcomments') ) {
			Schema::create('movie_subcomments', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('user_id')->nullable();
				$table->integer('comment_id')->nullable();
				$table->string('reply', 200)->nullable();
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
		Schema::drop('movie_subcomments');
	}

}
