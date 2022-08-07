<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeSlidersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('home_sliders') ) {
			Schema::create('home_sliders', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('movie_id')->unsigned()->nullable()->index('home_sliders_movie_id_foreign');
				$table->integer('tv_series_id')->unsigned()->nullable()->index('home_sliders_tv_series_id_foreign');
				$table->string('slide_image', 191)->nullable();
				$table->boolean('active')->default(0);
				$table->integer('position');
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
		Schema::drop('home_sliders');
	}

}
