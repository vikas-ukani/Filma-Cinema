<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateButtonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('buttons') ) {
			Schema::create('buttons', function(Blueprint $table)
			{
				$table->increments('id');
				$table->boolean('rightclick')->default(1);
				$table->boolean('inspect')->nullable();
				$table->boolean('goto')->default(1);
				$table->boolean('color')->default(1);
				$table->boolean('uc_browser')->default(1);
				$table->boolean('comming_soon')->default(1);
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
		Schema::drop('buttons');
	}

}
