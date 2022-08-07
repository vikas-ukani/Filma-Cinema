<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontSliderUpdatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('front_slider_updates') ) {
			Schema::create('front_slider_updates', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('item_show')->unsigned()->nullable();
				$table->integer('orderby')->nullable()->default(1);
				$table->boolean('sliderview');
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
		Schema::drop('front_slider_updates');
	}

}
