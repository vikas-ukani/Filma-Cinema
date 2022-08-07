<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLandingPagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('landing_pages') ) {
			Schema::create('landing_pages', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('image', 191)->nullable();
				$table->string('heading', 191)->nullable();
				$table->text('detail', 65535)->nullable();
				$table->boolean('button')->default(1);
				$table->string('button_text', 191)->nullable();
				$table->string('button_link', 191)->nullable();
				$table->boolean('left')->default(1);
				$table->integer('position')->nullable();
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
		Schema::drop('landing_pages');
	}

}
