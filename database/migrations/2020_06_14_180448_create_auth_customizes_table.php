<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthCustomizesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('auth_customizes') ) {
			Schema::create('auth_customizes', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('image', 191)->nullable();
				$table->text('detail', 65535)->nullable();
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
		Schema::drop('auth_customizes');
	}

}
