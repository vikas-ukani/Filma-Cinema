<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeBlocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('home_blocks') ) {
			Schema::create('home_blocks', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('movie_id')->nullable();
				$table->integer('tv_series_id')->nullable();
				$table->integer('is_active');
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
		Schema::drop('home_blocks');
	}

}
