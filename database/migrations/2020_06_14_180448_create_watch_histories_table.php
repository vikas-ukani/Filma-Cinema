<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWatchHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('watch_histories') ) {
			Schema::create('watch_histories', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('movie_id')->nullable();
				$table->integer('tv_id')->nullable();
				$table->integer('user_id')->nullable();
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
		Schema::drop('watch_histories');
	}

}
