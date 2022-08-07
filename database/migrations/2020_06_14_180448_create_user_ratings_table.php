<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRatingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('user_ratings') ) {
			Schema::create('user_ratings', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('user_id')->nullable();
				$table->integer('tv_id')->nullable();
				$table->integer('movie_id')->nullable();
				$table->float('rating', 10, 0)->nullable();
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
		Schema::drop('user_ratings');
	}

}
