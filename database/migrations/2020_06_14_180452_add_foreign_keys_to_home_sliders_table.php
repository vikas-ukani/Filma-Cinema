<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHomeSlidersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('home_sliders') ) {
			Schema::table('home_sliders', function(Blueprint $table)
			{
				$table->foreign('movie_id')->references('id')->on('movies')->onUpdate('RESTRICT')->onDelete('CASCADE');
				$table->foreign('tv_series_id')->references('id')->on('tv_series')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
		Schema::table('home_sliders', function(Blueprint $table)
		{
			$table->dropForeign('home_sliders_movie_id_foreign');
			$table->dropForeign('home_sliders_tv_series_id_foreign');
		});
	}

}
