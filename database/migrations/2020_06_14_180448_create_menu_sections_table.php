<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuSectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('menu_sections') ) {
			Schema::create('menu_sections', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('menu_id')->unsigned()->nullable();
				$table->integer('section_id')->unsigned()->nullable();
				$table->integer('item_limit')->unsigned()->nullable();
				$table->integer('view')->unsigned()->default(1);
				$table->integer('order')->unsigned()->default(1);
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
		Schema::drop('menu_sections');
	}

}
