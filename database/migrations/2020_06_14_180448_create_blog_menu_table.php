<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('blog_menu') ) {
			Schema::create('blog_menu', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('menu_id');
				$table->integer('blog_id')->unsigned();
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
		Schema::drop('blog_menu');
	}

}
